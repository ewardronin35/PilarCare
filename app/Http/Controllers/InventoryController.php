<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\LowStockEvent;
use PDF;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Notifications\InventoryExpiringNotification; 
use App\Notifications\LowStockNotification; // Import the new notification class

class InventoryController extends Controller
{
    /**
     * Display the inventory management page.
     */
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
                                  ->selectRaw('item_name, SUM(quantity) as total_quantity')
                                  ->groupBy('item_name')
                                  ->orderByDesc('total_quantity')
                                  ->limit(5)
                                  ->get();
        
        $equipmentUsage = Inventory::where('type', 'Equipment')
                                   ->selectRaw('item_name, SUM(quantity) as total_quantity')
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

    /**
     * Add a new inventory item.
     */
    public function add(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'supplier' => 'required|string|max:255',
            'type' => 'required|string|in:Equipment,Medicine',
            'date_acquired' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:date_acquired',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed when adding inventory item.', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create new inventory item
            $inventoryItem = Inventory::create($validator->validated());

            Log::info('Inventory item added successfully.', ['item' => $inventoryItem]);

            // Low Stock Check
            if ($inventoryItem->quantity <= 2) {
                Log::info('Low Stock Alert: ' . $inventoryItem->item_name . ' is low in stock.');

                // Fetch relevant users to notify (e.g., admins)
                $users = User::where('role', 'Admin')->get(); // Adjust the role as needed

                foreach ($users as $user) {
                    // Notify via Notification
                    $user->notify(new LowStockNotification($inventoryItem->item_name, $inventoryItem->quantity));

                    // Optionally, send an InventoryExpiringNotification if relevant
                    // $user->notify(new InventoryExpiringNotification($inventoryItem->item_name, $inventoryItem->expiry_date));
                }

                // Broadcast the Low Stock Event
                event(new LowStockEvent([
                    'title' => 'Low Stock Alert',
                    'message' => "The item {$inventoryItem->item_name} is low in stock with only {$inventoryItem->quantity} left.",
                    'expiry_date' => $inventoryItem->expiry_date,
                ]));
            }

            // Expiry Date Check
            $expiryDate = Carbon::parse($inventoryItem->expiry_date);
            $now = Carbon::now();
            if ($expiryDate->isBetween($now, $now->copy()->addDays(7))) {
                Log::info('Expiry Alert: ' . $inventoryItem->item_name . ' is expiring soon.');

                // Fetch relevant users to notify (e.g., admins)
                $users = User::where('role', 'Admin')->get(); // Adjust the role as needed

                foreach ($users as $user) {
                    // Notify via Notification
                    $user->notify(new InventoryExpiringNotification($inventoryItem->item_name, $inventoryItem->expiry_date));
                }

                // Optionally, dispatch a separate event if needed
                event(new LowStockEvent([
                    'title' => 'Item Expiry Alert',
                    'message' => "The item {$inventoryItem->item_name} is expiring on {$inventoryItem->expiry_date}.",
                    'expiry_date' => $inventoryItem->expiry_date,
                ]));
            }

            // Return Success Response
            return response()->json(['success' => true, 'message' => 'Item added successfully!']);
        } catch (\Exception $e) {
            // Log any errors
            Log::error('Error adding inventory item:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to add item.'], 500);
        }
    }

    /**
     * Update an existing inventory item.
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'supplier' => 'required|string|max:255',
            'type' => 'required|string|in:Equipment,Medicine',
            'date_acquired' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:date_acquired',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed when updating inventory item.', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find the inventory item
            $inventoryItem = Inventory::findOrFail($id);

            // Update the inventory item
            $inventoryItem->update($validator->validated());

            Log::info('Inventory item updated successfully.', ['item' => $inventoryItem]);

            // Low Stock Check
            if ($inventoryItem->quantity <= 2) {
                Log::info('Low Stock Alert: ' . $inventoryItem->item_name . ' is low in stock.');

                // Fetch relevant users to notify (e.g., admins)
                $users = User::where('role', 'Admin')->get(); // Adjust the role as needed

                foreach ($users as $user) {
                    // Notify via Notification
                    $user->notify(new LowStockNotification($inventoryItem->item_name, $inventoryItem->quantity));
                }

                // Broadcast the Low Stock Event
                event(new LowStockEvent([
                    'title' => 'Low Stock Alert',
                    'message' => "The item {$inventoryItem->item_name} is low in stock with only {$inventoryItem->quantity} left.",
                    'expiry_date' => $inventoryItem->expiry_date,
                ]));
            }

            // Expiry Date Check
            $expiryDate = Carbon::parse($inventoryItem->expiry_date);
            $now = Carbon::now();
            if ($expiryDate->isBetween($now, $now->copy()->addDays(7))) {
                Log::info('Expiry Alert: ' . $inventoryItem->item_name . ' is expiring soon.');

                // Fetch relevant users to notify (e.g., admins)
                $users = User::where('role', 'Admin')->get(); // Adjust the role as needed

                foreach ($users as $user) {
                    // Notify via Notification
                    $user->notify(new InventoryExpiringNotification($inventoryItem->item_name, $inventoryItem->expiry_date));
                }

                // Optionally, dispatch a separate event if needed
                event(new LowStockEvent([
                    'title' => 'Item Expiry Alert',
                    'message' => "The item {$inventoryItem->item_name} is expiring on {$inventoryItem->expiry_date}.",
                    'expiry_date' => $inventoryItem->expiry_date,
                ]));
            }

            return response()->json(['success' => true, 'message' => 'Item updated successfully!']);
        } catch (\Exception $e) {
            Log::error('Error updating inventory item:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to update item.'], 500);
        }
    }

    /**
     * Delete an inventory item.
     */
    public function delete($id)
    {
        try {
            $inventoryItem = Inventory::findOrFail($id);
            $inventoryItem->delete();

            Log::info('Inventory item deleted successfully.', ['item' => $inventoryItem]);

            // Optionally, notify users about the deletion
            // Fetch relevant users to notify (e.g., admins)
            $users = User::where('role', 'Admin')->get(); // Adjust the role as needed

            foreach ($users as $user) {
                $user->notify(new InventoryExpiringNotification($inventoryItem->item_name, $inventoryItem->expiry_date));
            }

            // Broadcast the Deletion Event if needed
            // event(new InventoryDeletionEvent([...]));

            return response()->json(['success' => 'Item deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Error deleting inventory item:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to delete item.'], 500);
        }
    }

    /**
     * Get available medicines.
     */
    public function getAvailableMedicines()
    {
        $medicines = Inventory::where('type', 'Medicine')->where('quantity', '>', 0)->pluck('item_name');
        return response()->json($medicines);
    }

    /**
     * Update quantity of a medicine.
     */
    public function updateQuantity(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'medicine' => 'required|string|exists:inventories,item_name',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed when updating inventory quantity.', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $inventoryItem = Inventory::where('item_name', $request->medicine)->firstOrFail();
            if ($inventoryItem->quantity <= 0) {
                Log::warning('Attempted to decrement inventory for out-of-stock medicine.', ['item' => $inventoryItem]);
                return response()->json(['success' => false, 'message' => 'Medicine is out of stock.'], 400);
            }

            $inventoryItem->quantity -= 1; // Decrement quantity
            $inventoryItem->save();

            Log::info('Inventory quantity updated successfully.', ['item' => $inventoryItem]);

            // Check if quantity is low after decrement
            if ($inventoryItem->quantity <= 2) {
                Log::info('Low Stock Alert: ' . $inventoryItem->item_name . ' is low in stock.');

                // Fetch relevant users to notify (e.g., admins)
                $users = User::where('role', 'Admin')->get(); // Adjust the role as needed

                foreach ($users as $user) {
                    // Notify via Notification
                    $user->notify(new LowStockNotification($inventoryItem->item_name, $inventoryItem->quantity));
                }

                // Broadcast the Low Stock Event
                event(new LowStockEvent([
                    'title' => 'Low Stock Alert',
                    'message' => "The item {$inventoryItem->item_name} is low in stock with only {$inventoryItem->quantity} left.",
                    'expiry_date' => $inventoryItem->expiry_date,
                ]));
            }

            return response()->json(['success' => true, 'message' => 'Inventory updated successfully!']);
        } catch (\Exception $e) {
            Log::error('Error updating inventory quantity:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to update inventory.'], 500);
        }
    }

    /**
     * Generate statistics report.
     */
    public function generateStatisticsReport(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'report_period' => 'required|string|in:week,month,year',
            'report_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed when generating inventory report.', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $reportPeriod = $request->input('report_period');
        $reportDate = $request->input('report_date');

        // Determine the start and end dates based on the report period
        switch ($reportPeriod) {
            case 'week':
                $startDate = Carbon::parse($reportDate)->startOfWeek();
                $endDate = Carbon::parse($reportDate)->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::parse($reportDate)->startOfMonth();
                $endDate = Carbon::parse($reportDate)->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::parse($reportDate)->startOfYear();
                $endDate = Carbon::parse($reportDate)->endOfYear();
                break;
            default:
                Log::warning('Invalid report period provided.', ['report_period' => $reportPeriod]);
                return response()->json(['success' => false, 'message' => 'Invalid report period.'], 400);
        }

        try {
            // Fetch inventory data within the specified period
            $inventoryData = Inventory::whereBetween('date_acquired', [$startDate, $endDate])->get();

            // Fetch additional statistics as needed
            $totalItems = Inventory::count();
            $totalQuantity = Inventory::sum('quantity');

            // Prepare data for the report
            $data = [
                'report_period' => ucfirst($reportPeriod),
                'report_date' => Carbon::parse($reportDate)->toFormattedDateString(),
                'inventoryData' => $inventoryData,
                'totalItems' => $totalItems,
                'totalQuantity' => $totalQuantity,
            ];

            // Generate PDF using Blade view
            $pdf = PDF::loadView('pdf.inventory_report', $data); // Ensure you have this view

            // Save the PDF to storage (optional)
            $fileName = 'Inventory_Report_' . now()->timestamp . '.pdf';
            $pdfPath = 'reports/' . $fileName;
            Storage::disk('public')->put($pdfPath, $pdf->output());

            // Log the report generation
            Log::info('Inventory statistics report generated.', ['file' => $pdfPath]);

            // Return the URL to download the report
            $pdfUrl = asset('storage/' . $pdfPath);

            return response()->json(['success' => true, 'pdf_url' => $pdfUrl]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error generating inventory report:', ['error' => $e->getMessage()]);

            return response()->json(['success' => false, 'message' => 'Failed to generate the report.'], 500);
        }
    }
    public function checkLowStock($item)
{
    if ($item->quantity <= 2) {
        $data = [
            'title' => 'Low Stock Alert',
            'message' => "The item '{$item->item_name}' is low in stock with only {$item->quantity} left.",
            'expiry_date' => $item->expiry_date,
        ];

        // Dispatch the event
        event(new LowStockEvent($data));

        // Notify the user(s)
        $user = User::where('role', 'Admin')->first();
        $user->notify(new LowStockNotification($item->item_name, $item->quantity));
    }
}
}
