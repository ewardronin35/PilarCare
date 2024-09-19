<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\LowStockNotification;

class InventoryController extends Controller
{
    public function index()
    {
        // Fetch all inventory items
        $inventoryItems = Inventory::all();

        // Gather statistics for the most used items (medicines and equipment)
        $inventoryStats = [
            'items' => [],
            'usage' => []
        ];

        // Fetch statistics for most used medicines and equipment
        $medicineUsage = Inventory::where('type', 'Medicine')
                                  ->select(DB::raw('item_name, SUM(quantity) as total_quantity'))
                                  ->groupBy('item_name')
                                  ->orderByDesc('total_quantity')
                                  ->limit(5)
                                  ->get();
        
        $equipmentUsage = Inventory::where('type', 'Equipment')
                                   ->select(DB::raw('item_name, SUM(quantity) as total_quantity'))
                                   ->groupBy('item_name')
                                   ->orderByDesc('total_quantity')
                                   ->limit(5)
                                   ->get();

        // Combine the data for display in the chart
        foreach ($medicineUsage as $medicine) {
            $inventoryStats['items'][] = $medicine->item_name;
            $inventoryStats['usage'][] = $medicine->total_quantity;
        }

        foreach ($equipmentUsage as $equipment) {
            $inventoryStats['items'][] = $equipment->item_name;
            $inventoryStats['usage'][] = $equipment->total_quantity;
        }

        // Pass data to the view, including the inventory statistics
        return view('admin.inventory', compact('inventoryItems', 'inventoryStats'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'item_name' => 'required|string|max:255',
        'quantity' => 'required|integer',
        'supplier' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'date_acquired' => 'required|date',
    ]);

    try {
        $inventoryItem = Inventory::findOrFail($id);
        $inventoryItem->update($request->all());

        if ($inventoryItem->quantity <= 2) {
            \Log::info('Low Stock Alert: ' . $inventoryItem->item_name . ' is low in stock.');
            
            // Create the notification
            Notification::create([
                'user_id' => Auth::user()->id_number,  
                'title' => 'Low Stock Alert',
                'message' => "The item {$inventoryItem->item_name} is low in stock with only {$inventoryItem->quantity} left.",
            ]);

            // Broadcast the low stock event using Pusher
            event(new LowStockNotification("Low stock alert: {$inventoryItem->item_name} only has {$inventoryItem->quantity} left."));
        }

        \Log::info('Inventory item updated successfully.', ['item' => $inventoryItem]);
        return response()->json(['success' => 'Item updated successfully!']);
    } catch (\Exception $e) {
        \Log::error('Error updating inventory item:', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Failed to update item.'], 500);
    }
}

    public function delete($id)
    {
        $inventoryItem = Inventory::findOrFail($id);
        $inventoryItem->delete();

        return response()->json(['success' => 'Item deleted successfully!']);
    }
    public function getAvailableMedicines()
    {
        $medicines = Inventory::where('quantity', '>', 0)->pluck('item_name');
        return response()->json($medicines);
    }
    public function updateQuantity(Request $request)
{
    $request->validate([
        'medicine' => 'required|string',
    ]);

    try {
        $inventoryItem = Inventory::where('item_name', $request->medicine)->first();
        if ($inventoryItem) {
            $inventoryItem->quantity -= 1; // Decrement quantity
            $inventoryItem->save();
            return response()->json(['success' => true, 'message' => 'Inventory updated successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Medicine not found in inventory.'], 404);
        }
    } catch (\Exception $e) {
        \Log::error('Error updating inventory quantity:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Failed to update inventory.'], 500);
    }
}
public function notifyLowStock()
{
    event(new LowStockNotification("Low stock alert: {$inventoryItem->item_name} only has {$inventoryItem->quantity} left."));
}

public function add(Request $request)
{
    // Validate incoming request
    $request->validate([
        'item_name' => 'required|string|max:255',
        'quantity' => 'required|integer|min:1', // Ensure quantity is at least 1
        'supplier' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'date_acquired' => 'required|date',
    ]);

    try {
        // Create new inventory item
        $inventoryItem = new Inventory();
        $inventoryItem->item_name = $request->input('item_name');
        $inventoryItem->quantity = $request->input('quantity');
        $inventoryItem->supplier = $request->input('supplier');
        $inventoryItem->type = $request->input('type');
        $inventoryItem->date_acquired = $request->input('date_acquired');
        
        // Save the item to the database
        $inventoryItem->save();

        // Check if the quantity is low and notify if applicable
        if ($inventoryItem->quantity <= 2) {
            // Log low stock alert
            \Log::info('Low Stock Alert: ' . $inventoryItem->item_name . ' is low in stock.');
            
            // Create a notification for low stock
            Notification::create([
                'user_id' => Auth::user()->id_number,  // Assuming user ID is used for notifications
                'title' => 'Low Stock Alert',
                'message' => "The item {$inventoryItem->item_name} is low in stock with only {$inventoryItem->quantity} left.",
            ]);

            // Trigger Pusher event for low stock notification
            event(new LowStockNotification("Low stock alert: {$inventoryItem->item_name} only has {$inventoryItem->quantity} left."));
        }

        // Return success response
        return response()->json(['success' => true, 'message' => 'Item added successfully!']);
    } catch (\Exception $e) {
        // Log any errors
        \Log::error('Error adding inventory item:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Failed to add item.'], 500);
    }
}

}
