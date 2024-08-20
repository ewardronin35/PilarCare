<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $inventoryItems = Inventory::all();
        return view('admin.inventory', compact('inventoryItems'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'supplier' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'date_acquired' => 'required|date',
        ]);

        $inventoryItem = Inventory::create($request->all());

        if ($inventoryItem->quantity <= 2) {
            Notification::create([
                'user_id' => Auth::user()->id_number,  // Assuming 'id_number' is used for user identification
                'title' => 'Low Stock Alert',
                'message' => "The item {$inventoryItem->item_name} is low in stock with only {$inventoryItem->quantity} left.",
            ]);
        }

        return response()->json(['success' => 'Item added successfully!']);
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
                Notification::create([
                    'user_id' => Auth::user()->id_number,  
                    'title' => 'Low Stock Alert',
                    'message' => "The item {$inventoryItem->item_name} is low in stock with only {$inventoryItem->quantity} left.",
                ]);
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

}
