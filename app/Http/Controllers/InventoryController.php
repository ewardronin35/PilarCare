<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Nurse;
use App\Models\Notification; // Import your custom Notification model

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

    // Get the current authenticated user
    $user = Auth::user();

    // Determine the view based on the user's role
    if ($user->role === 'Admin') {
        $view = 'admin.inventory';
    } elseif ($user->role === 'Nurse') {
        $view = 'nurse.inventory';
    } else {
        // Optionally, handle unauthorized access
        abort(403, 'Unauthorized action.');
    }

    // Pass data to the view, including the inventory statistics
    return view($view, compact('inventoryItems', 'inventoryStats'));
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
            'generic_name' => 'required|string|max:255',
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

                // Fetch relevant users to notify (Admins and Nurses)
                $users = User::whereIn('role', ['Admin', 'Nurse'])->get();

                foreach ($users as $user) {
                    // Create a new notification entry
                    Notification::create([
                        'user_id' => $user->id_number, // Ensure this matches your foreign key setup
                        'title' => 'Low Stock Alert',
                        'message' => "The item '{$inventoryItem->item_name}' is low in stock with only {$inventoryItem->quantity} left.",
                        'scheduled_time' => now(),
                        'role' => $user->role,
                        'is_opened' => false,
                    ]);
                }
            }

            // Expiry Date Check
            $expiryDate = Carbon::parse($inventoryItem->expiry_date);
            $now = Carbon::now();
            if ($expiryDate->isBetween($now, $now->copy()->addDays(7))) {
                Log::info('Expiry Alert: ' . $inventoryItem->item_name . ' is expiring soon.');

                // Fetch relevant users to notify (Admins and Nurses)
                $users = User::whereIn('role', ['Admin', 'Nurse'])->get();

                foreach ($users as $user) {
                    // Create a new notification entry
                    Notification::create([
                        'user_id' => $user->id_number,
                        'title' => 'Inventory Expiry Alert',
                        'message' => "The item '{$inventoryItem->item_name}' is expiring on {$inventoryItem->expiry_date->toFormattedDateString()}.",
                        'scheduled_time' => now(),
                        'role' => $user->role,
                        'is_opened' => false,
                    ]);
                }
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
            'generic_name' => 'required|string|max:255',
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

                // Fetch relevant users to notify (Admins and Nurses)
                $users = User::whereIn('role', ['Admin', 'Nurse'])->get();
                foreach ($users as $user) {
                    // Create a new notification entry
                    Notification::create([
                        'user_id' => $user->id_number,
                        'title' => 'Low Stock Alert',
                        'message' => "The item '{$inventoryItem->item_name}' is low in stock with only {$inventoryItem->quantity} left.",
                        'scheduled_time' => now(),
                        'role' => $user->role,
                        'is_opened' => false,
                    ]);
                }
            }

            // Expiry Date Check
            $expiryDate = Carbon::parse($inventoryItem->expiry_date);
            $now = Carbon::now();
            if ($expiryDate->isBetween($now, $now->copy()->addDays(7))) {
                Log::info('Expiry Alert: ' . $inventoryItem->item_name . ' is expiring soon.');

                // Fetch relevant users to notify (Admins and Nurses)
                $users = User::whereIn('role', ['Admin', 'Nurse'])->get();

                foreach ($users as $user) {
                    // Create a new notification entry
                    Notification::create([
                        'user_id' => $user->id_number,
                        'title' => 'Inventory Expiry Alert',
                        'message' => "The item '{$inventoryItem->item_name}' is expiring on {$inventoryItem->expiry_date->toFormattedDateString()}.",
                        'scheduled_time' => now(),
                        'role' => $user->role,
                        'is_opened' => false,
                    ]);
                }
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

            // Notify users about the deletion (Admins and Nurses)
            $users = User::whereIn('role', ['Admin', 'Nurse'])->get();

            foreach ($users as $user) {
                // Create a new notification entry
                Notification::create([
                    'user_id' => $user->id_number,
                    'title' => 'Inventory Deletion Alert',
                    'message' => "The item '{$inventoryItem->item_name}' has been deleted from the inventory.",
                    'scheduled_time' => now(),
                    'role' => $user->role,
                    'is_opened' => false,
                ]);
            }

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

                // Fetch relevant users to notify (Admins and Nurses)
                $users = User::whereIn('role', ['Admin', 'Nurse'])->get();

                foreach ($users as $user) {
                    // Create a new notification entry
                    Notification::create([
                        'user_id' => $user->id_number,
                        'title' => 'Low Stock Alert',
                        'message' => "The item '{$inventoryItem->item_name}' is low in stock with only {$inventoryItem->quantity} left.",
                        'scheduled_time' => now(),
                        'role' => $user->role,
                        'is_opened' => false,
                    ]);
                }
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
            'report_period' => 'required|string|in:daily,weekly,monthly,yearly',
            'report_date' => 'required|date',
        ]);
    
        if ($validator->fails()) {
            Log::warning('Validation failed when generating inventory report.', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $reportPeriod = $request->input('report_period');
        $reportDate = $request->input('report_date');
    
        // Determine the start and end dates based on the report period
        switch ($reportPeriod) {
            case 'daily':
                $startDate = Carbon::parse($reportDate)->startOfDay();
                $endDate = Carbon::parse($reportDate)->endOfDay();
                break;
            case 'weekly':
                $startDate = Carbon::parse($reportDate)->startOfWeek();
                $endDate = Carbon::parse($reportDate)->endOfWeek();
                break;
            case 'monthly':
                $startDate = Carbon::parse($reportDate)->startOfMonth();
                $endDate = Carbon::parse($reportDate)->endOfMonth();
                break;
            case 'yearly':
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
    
            // Calculate statistics
            $totalItems = Inventory::count();
            $totalQuantity = Inventory::sum('quantity');
    
            // Calculate usage statistics: most used items based on quantity
            $mostUsedItems = Inventory::orderByDesc('quantity')->take(5)->get();
    
            // Reorder Recommendations: Items with quantity <= threshold (e.g., 5)
            $reorderThreshold = 5;
            $reorderRecommendations = Inventory::where('quantity', '<=', $reorderThreshold)->get();
    
            // Identify items expiring within the next 30 days
            $expiringSoon = Inventory::where('expiry_date', '<=', Carbon::now()->addDays(30))
                                    ->where('expiry_date', '>=', Carbon::now())
                                    ->get();
    
            // Generate Chart Image URL using QuickChart
            $chartConfig = [
                'type' => 'bar',
                'data' => [
                    'labels' => $mostUsedItems->pluck('item_name')->toArray(),
                    'datasets' => [[
                        'label' => 'Quantity Used',
                        'data' => $mostUsedItems->pluck('quantity')->toArray(),
                        'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                        'borderColor' => 'rgba(54, 162, 235, 1)',
                        'borderWidth' => 1
                    ]]
                ],
                'options' => [
                    'plugins' => [
                        'legend' => ['display' => false],
                        'title' => ['display' => true, 'text' => 'Top 5 Most Used Inventory Items']
                    ],
                    'scales' => [
                        'y' => ['beginAtZero' => true, 'title' => ['display' => true, 'text' => 'Quantity Used']],
                        'x' => ['title' => ['display' => true, 'text' => 'Item Name']]
                    ]
                ]
            ];
    
            $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig));
    
            // Prepare data for the report
            $data = [
                'report_period' => ucfirst($reportPeriod),
                'report_date' => Carbon::parse($reportDate)->toFormattedDateString(),
                'inventoryData' => $inventoryData,
                'totalItems' => $totalItems,
                'totalQuantity' => $totalQuantity,
                'mostUsedItems' => $mostUsedItems,
                'reorderRecommendations' => $reorderRecommendations,
                'expiringSoon' => $expiringSoon,
                'chartUrl' => $chartUrl,
                'logoBase64' => file_exists(public_path('images/pilarLogo.png')) ? base64_encode(file_get_contents(public_path('images/pilarLogo.png'))) : null,
            ];
    
            // Log the report generation details
            Log::info('Generating Inventory Statistics Report', [
                'report_period' => $reportPeriod,
                'report_date' => $reportDate,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'total_items' => $totalItems,
                'total_quantity' => $totalQuantity,
                'most_used_items' => $mostUsedItems->toArray(),
                'reorder_recommendations' => $reorderRecommendations->toArray(),
                'expiring_soon' => $expiringSoon->toArray(),
            ]);
    
            // Generate PDF using Blade view
            $pdf = PDF::loadView('pdf.inventory_report', $data);
    
            // Create a unique filename using timestamp
            $fileName = 'Inventory_Report_' . now()->timestamp . '.pdf';
            $pdfDirectory = 'reports'; // Define a directory within the public disk
            $pdfPath = "{$pdfDirectory}/{$fileName}"; // Relative path within storage/app/public
    
            // Ensure the directory exists
            Storage::disk('public')->makeDirectory($pdfDirectory);
    
            // Check and delete existing file if it exists (optional since filename is unique)
            if (Storage::disk('public')->exists($pdfPath)) {
                Storage::disk('public')->delete($pdfPath);
                Log::info("Deleted existing report: {$pdfPath}");
            }
    
            // Save the PDF file
            Storage::disk('public')->put($pdfPath, $pdf->output());
            Log::info("Inventory Statistics Report generated and saved to {$pdfPath}");
    
            // Return the URL to download the report
            $pdfUrl = asset('storage/' . $pdfPath);
    
            return response()->json(['success' => true, 'pdf_url' => $pdfUrl]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error generating inventory report:', ['error' => $e->getMessage()]);
    
            return response()->json(['success' => false, 'message' => 'Failed to generate the report.'], 500);
        }
    }
    

    /**
     * Check and create low stock notifications.
     */
    public function checkLowStock($item)
    {
        if ($item->quantity <= 1) {
            // Notify relevant users (Admins and Nurses)
            $users = User::whereIn('role', ['Admin', 'Nurse'])->get();

            foreach ($users as $user) {
                Notification::create([
                    'user_id' => $user->id_number,
                    'title' => 'Low Stock Alert',
                    'message' => "The item '{$item->item_name}' is low in stock with only {$item->quantity} left.",
                    'scheduled_time' => now(),
                    'role' => $user->role,
                    'is_opened' => false,
                ]);
            }
        }
    }
    public function getInventoryPredictions()
    {
        $user = Auth::user();
        $role = strtolower($user->role);

        // Only allow specific roles to access predictions
        if (!in_array($role, ['admin', 'nurse', 'doctor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Fetch all inventory items
        $inventoryItems = Inventory::all();

        // Ensure there is enough data for prediction
        if ($inventoryItems->count() < 5) { // Arbitrary threshold
            return response()->json([
                'success' => false,
                'message' => 'Not enough inventory data to generate predictions.'
            ], 400);
        }

        // **Predict Next Likely Medicine to Run Out**
        $nextMedicine = $this->predictNextMedicine($inventoryItems);

        // **Predict Next Likely Equipment to Require Maintenance**
        $nextEquipment = $this->predictNextEquipment($inventoryItems);

        return response()->json([
            'success' => true,
            'next_medicine_to_restock' => $nextMedicine,
            'next_equipment_to_maintain' => $nextEquipment,
        ], 200);
    }

    /**
     * Predict the next likely medicine to run out based on usage frequency.
     *
     * @param \Illuminate\Support\Collection $inventoryItems
     * @return string
     */
    private function predictNextMedicine($inventoryItems)
    {
        // Filter only medicines
        $medicines = $inventoryItems->where('type', 'Medicine');

        if ($medicines->isEmpty()) {
            return 'N/A';
        }

        // Assume 'reorder_level' and 'quantity' fields exist
        // Predict the medicine closest to reorder level based on current quantity
        $medicineToRestock = $medicines->sortBy('quantity')->first();

        return $medicineToRestock->item_name ?? 'N/A';
    }

    /**
     * Predict the next likely equipment to require maintenance or replacement.
     *
     * @param \Illuminate\Support\Collection $inventoryItems
     * @return string
     */
    private function predictNextEquipment($inventoryItems)
    {
        // Filter only equipment
        $equipments = $inventoryItems->where('type', 'Equipment');

        if ($equipments->isEmpty()) {
            return 'N/A';
        }

        // Assume 'last_maintenance_date' field exists
        // Predict equipment with the longest time since last maintenance
        $equipmentToMaintain = $equipments->sortByDesc(function($item) {
            return Carbon::parse($item->last_maintenance_date ?? '1970-01-01');
        })->first();

        return $equipmentToMaintain->item_name ?? 'N/A';
    }

    /**
     * Update quantity of a medicine (e.g., when dispensed).
     */
    public function dispenseMedicine(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'medicine_id' => 'required|integer|exists:inventories,id',
            'quantity_dispensed' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed when dispensing medicine.', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $inventoryItem = Inventory::findOrFail($request->medicine_id);

            if ($inventoryItem->quantity < $request->quantity_dispensed) {
                Log::warning('Attempted to dispense more than available stock.', ['item' => $inventoryItem]);
                return response()->json(['success' => false, 'message' => 'Insufficient stock available.'], 400);
            }

            $inventoryItem->quantity -= $request->quantity_dispensed;
            $inventoryItem->save();

            Log::info('Medicine dispensed successfully.', ['item' => $inventoryItem, 'quantity_dispensed' => $request->quantity_dispensed]);

            // Check if quantity is low after dispensing
            if ($inventoryItem->quantity <= $inventoryItem->reorder_level) {
                Log::info('Low Stock Alert: ' . $inventoryItem->item_name . ' is low in stock.');

                // Fetch relevant users to notify (Admins and Nurses)
                $users = User::whereIn('role', ['Admin', 'Nurse'])->get();

                foreach ($users as $user) {
                    // Create a new notification entry
                    Notification::create([
                        'user_id' => $user->id,
                        'title' => 'Low Stock Alert',
                        'message' => "The item '{$inventoryItem->item_name}' is low in stock with only {$inventoryItem->quantity} left.",
                        'scheduled_time' => now(),
                        'role' => $user->role,
                        'is_opened' => false,
                    ]);
                }
            }

            return response()->json(['success' => true, 'message' => 'Medicine dispensed successfully!']);
        } catch (\Exception $e) {
            Log::error('Error dispensing medicine:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to dispense medicine.'], 500);
        }
    }
}
