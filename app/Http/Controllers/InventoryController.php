<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function index()
    {
        $inventoryItems = Inventory::all();
        return view('inventory', compact('inventoryItems'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'item_id' => 'required|string|max:255|unique:inventory',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'supplier' => 'required|string|max:255',
            'date_acquired' => 'required|date',
        ]);

        Inventory::create($request->all());

        return redirect()->route('inventory')->with('success', 'Item added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_id' => 'required|string|max:255|unique:inventory,item_id,' . $id,
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'supplier' => 'required|string|max:255',
            'date_acquired' => 'required|date',
        ]);

        $inventoryItem = Inventory::findOrFail($id);
        $inventoryItem->update($request->all());

        return redirect()->route('inventory')->with('success', 'Item updated successfully!');
    }

    public function delete($id)
    {
        $inventoryItem = Inventory::findOrFail($id);
        $inventoryItem->delete();

        return redirect()->route('inventory')->with('success', 'Item deleted successfully!');
    }
}






