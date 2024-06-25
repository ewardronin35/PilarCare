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
                'user_id' => 'admin',  // Assuming 'admin' is used for user identification
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

        $inventoryItem = Inventory::findOrFail($id);
        $inventoryItem->update($request->all());

        if ($inventoryItem->quantity <= 2) {
            Notification::create([
                'user_id' => 'admin',  // Assuming 'admin' is used for user identification
                'title' => 'Low Stock Alert',
                'message' => "The item {$inventoryItem->item_name} is low in stock with only {$inventoryItem->quantity} left.",
            ]);
        }

        return response()->json(['success' => 'Item updated successfully!']);
    }

    public function delete($id)
    {
        $inventoryItem = Inventory::findOrFail($id);
        $inventoryItem->delete();

        return response()->json(['success' => 'Item deleted successfully!']);
    }
}
