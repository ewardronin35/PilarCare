<x-app-layout :pageTitle="'Medical Inventory'">   
    <!-- Google Fonts and Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            opacity: 0;
            animation: fadeIn 1s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        /* Container Styles */
        .container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
            animation: fadeIn 1s forwards;
        }

        .main-content {
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
            margin-top: 30px;
            animation: fadeIn 1s forwards;
        }

        /* Form, Table, and Chart Containers */
        .form-container, .table-container, .chart-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s forwards;
        }

        .form-container {
            width: 100%;
            max-width: 450px;
            animation: slideInLeft 0.6s ease-in-out;
        }

        .table-container {
            flex: 1;
            overflow-y: auto; /* Ensures scrollability */
            animation: slideInRight 0.6s ease-in-out;
        }

        .chart-and-report {
            display: flex;
            width: 100%;
            flex-wrap: wrap;
        }

        .chart-container, .report-container {
            flex: 1 1 50%;
            box-sizing: border-box;
        }

        /* Inventory Table Styles */
        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
        }

        .inventory-table th, .inventory-table td {
            padding: 12px;
            text-align: left;
            white-space: nowrap;
        }

        .inventory-table th {
            background-color: #f5f5f5;
            color: #333;
            font-weight: 600;
            border-bottom: 1px solid #ddd;
        }

        .inventory-table td {
            border-bottom: 1px solid #ddd;
        }

        /* Form Group Styles */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 1rem;
            margin-bottom: 5px;
            display: block;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        /* Button Styles */
        .form-group button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1rem;
            margin-right: 10px; /* Adds spacing between buttons */
        }

        .form-group button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        /* Tabs Styles */
        .tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
            justify-content: space-around;
            animation: fadeIn 1s forwards;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            background-color: #e0e0e0;
            border-radius: 10px 10px 0 0;
            flex: 1;
            margin: 0 5px;
        }

        .tab:hover {
            background-color: #c9d1d9;
        }

        .tab.active {
            background-color: #007bff;
            color: white;
        }

        /* Tab Content Styles */
        .tab-content {
            display: none;
            animation: fadeIn 1s forwards;
        }

        .tab-content.active {
            display: block;
        }

        /* Action Buttons Styles */
        .action-buttons button {
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
    font-size: 0.9rem;
    margin-right: 5px;
}

        .action-buttons button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .action-buttons button:active {
            transform: scale(0.95);
        }

        /* Heading Styles */
        h1 {
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
            animation: fadeIn 1s forwards;
        }

        h2 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #333;
        }

        p {
            font-weight: 400;
        }

        /* Input Hover Effects */
        .form-group input:hover, .form-group select:hover {
            border-color: #00d1ff;
            transition: border-color 0.3s;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .form-container, .table-container {
                width: 100%;
            }

            .inventory-table {
                display: block;
                overflow-x: auto;
            }

            .chart-and-report {
                flex-direction: column;
            }

            .chart-container, .report-container {
                max-width: 100%;
            }
        }

        /* Modal Styles */
        .modal {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.4s ease-in-out, visibility 0.4s ease-in-out;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5); /* Semi-transparent dark overlay */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transform: translateY(-20px);
            transition: transform 0.4s ease-in-out;
        }

        .modal.active .modal-content {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .modal-header h2 {
            font-size: 1.5rem;
            margin: 0;
            color: #333;
        }

        .modal-header button {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #333;
        }

        .modal-body {
            padding: 10px 0;
        }

        .modal-footer {
            text-align: right;
            margin-top: 15px;
        }

        .modal-footer button {
            background-color: #007bff;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin-left: 10px;
            transition: background-color 0.3s;
        }

        .modal-footer button:hover {
            background-color: #0056b3;
        }
        /* Edit and Delete Button Styles */

.action-buttons button:hover {
    background-color: #0056b3; /* Darker shade on hover */
    transform: scale(1.05);
}

.action-buttons button:active {
    transform: scale(0.95);
}

/* Specific Styles for Edit and Delete Button */
.edit-button {
    background-color: #28a745; /* Green color for Edit */
}

.delete-button {
    background-color: #dc3545; /* Red color for Delete */
}

.edit-button:hover {
    background-color: #218838; /* Darker green on hover */
}

.delete-button:hover {
    background-color: #c82333; /* Darker red on hover */
}

    </style>

    <main class="main-content">
        <h1><i class="fas fa-box"></i> Inventory Management</h1>
      
        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" id="inventory-tab" onclick="switchTab('inventory')"><i class="fas fa-clipboard-list"></i> Inventory Table</div>
            <div class="tab" id="stats-tab" onclick="switchTab('stats')"><i class="fas fa-chart-bar"></i> Inventory Statistics</div>
        </div>

        <!-- Tab Content -->
        <div id="inventory-content" class="tab-content active">
            <div class="container">
                <!-- Add Inventory Item Form -->
                <div class="form-container">
                    <h2><i class="fas fa-plus-circle"></i> Add Inventory Item</h2>
                    <form id="add-form">
                        @csrf
                        <div class="form-group">
                            <label for="item-name">Item Name</label>
                            <input type="text" id="item-name" name="item_name" required>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" name="quantity" required>
                        </div>
                        <div class="form-group">
                            <label for="supplier">Brand</label>
                            <input type="text" id="supplier" name="supplier" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select id="type" name="type" required>
                                <option value="Equipment">Equipment</option>
                                <option value="Medicine">Medicine</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date-acquired">Date Acquired</label>
                            <input type="date" id="date-acquired" name="date_acquired" required>
                        </div>
                        <div class="form-group">
                            <label for="expiry-date">Expiry Date</label>
                            <input type="date" id="expiry-date" name="expiry_date" required>
                        </div>
                        <div class="form-group">
                            <button type="button" onclick="addItem()">Add Item</button>
                            <button type="button" onclick="clearAddForm()">Clear Form</button>
                        </div>
                    </form>
                </div>

                <!-- Inventory Table -->
                <div class="table-container">
                    <h2><i class="fas fa-table"></i> Inventory Table</h2>
                    <table id="inventory-table" class="inventory-table">
                    <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Brand</th>
                                <th>Type</th>
                                <th>Date Acquired</th>
                                <th>Expiry Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventoryItems as $item)
                                <tr id="item-row-{{ $item->id }}">
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->supplier }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->date_acquired)->format('Y-m-d') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->expiry_date)->format('Y-m-d') }}</td>
                                    <td>
                                    <div class="action-buttons">
    <button class="edit-button" onclick="openEditModal({{ $item->id }})"><i class="fas fa-edit"></i> Edit</button>
    <button class="delete-button" onclick="confirmDelete({{ $item->id }})"><i class="fas fa-trash"></i> Delete</button>
</div>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
       
            </div>
            
        </div>

        <!-- Inventory Statistics Tab Content -->
        <div id="stats-content" class="tab-content">
            <div class="container">
                <div class="chart-and-report">
                    <!-- Inventory Statistics Chart -->
                    <div class="chart-container">
                        <h2><i class="fas fa-chart-line"></i> Inventory Statistics</h2>
                        <canvas id="inventory-chart" style="max-width: 100%;"></canvas>
                    </div>

                    <!-- Generate Inventory Statistics Report -->
                    <div class="form-container">
                        <h2><i class="fas fa-file-pdf"></i> Generate Inventory Statistics Report</h2>
                        <form id="inventory-report-form">
                            @csrf
                            <div class="form-group">
                                <label for="report-period">Select Report Period</label>
                                <select id="report-period" name="report_period" required>
                                    <option value="week">Weekly</option>
                                    <option value="month">Monthly</option>
                                    <option value="year">Yearly</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="report-date">Select Date</label>
                                <input type="date" id="report-date" name="report_date" required>
                            </div>
                            <div class="form-group">
                                <button type="button" onclick="generateInventoryReport()">Generate Report</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Edit Modal HTML Structure -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Inventory Item</h2>
                <button onclick="closeEditModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="edit-form">
                    @csrf
                    <input type="hidden" id="edit-item-id" name="id">
                    <div class="form-group">
                        <label for="edit-item-name">Item Name</label>
                        <input type="text" id="edit-item-name" name="item_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-quantity">Quantity</label>
                        <input type="number" id="edit-quantity" name="quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-supplier">Brand</label>
                        <input type="text" id="edit-supplier" name="supplier" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-type">Type</label>
                        <select id="edit-type" name="type" required>
                            <option value="Equipment">Equipment</option>
                            <option value="Medicine">Medicine</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-date-acquired">Date Acquired</label>
                        <input type="date" id="edit-date-acquired" name="date_acquired" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-expiry-date">Expiry Date</label>
                        <input type="date" id="edit-expiry-date" name="expiry_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button onclick="closeEditModal()">Close</button>
                <button type="button" onclick="updateItem()">Update</button>
            </div>
        </div>
    </div>

    <!-- Script Section -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<!-- DataTables JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTables for the inventory table
    $('#inventory-table').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        order: [[4, 'desc']], // Example: Sort by Date Acquired by default
        columnDefs: [
            { orderable: false, targets: 6 } // Disable ordering on Actions column
        ]
    });
});

        // Clear Add Form Function
        function clearAddForm() {
            document.getElementById('add-form').reset();
        }

        // Clear Report Form Function
        function clearReportForm() {
            document.getElementById('inventory-report-form').reset();
        }

        // Open Edit Modal Function with Fade-In Animation
        function openEditModal(id) {
            const item = document.getElementById(`item-row-${id}`);
            if (!item) return;

            console.log(`Opening edit modal for item ID: ${id}`); // Debugging line

            document.getElementById('edit-item-id').value = id;
            document.getElementById('edit-item-name').value = item.children[0].innerText;
            document.getElementById('edit-quantity').value = item.children[1].innerText;
            document.getElementById('edit-supplier').value = item.children[2].innerText;
            document.getElementById('edit-type').value = item.children[3].innerText;
            document.getElementById('edit-date-acquired').value = formatDateForInput(item.children[4].innerText);
            document.getElementById('edit-expiry-date').value = formatDateForInput(item.children[5].innerText);

            const modal = document.getElementById('edit-modal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
            document.getElementById('edit-item-name').focus(); // Set focus to first input
        }

        // Close Edit Modal Function with Fade-Out Animation
        function closeEditModal() {
            const modal = document.getElementById('edit-modal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto'; // Restore background scrolling
        }

        // Format Date for Input Function
        function formatDateForInput(dateString) {
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Add Item Function
        function addItem() {
            const form = document.getElementById('add-form');
            const formData = new FormData(form);

            // Show SweetAlert loading spinner
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we add the item.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route("admin.inventory.add") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Invalid JSON response');
                }

                return response.json();
            })
            .then(data => {
                Swal.close(); // Close the loading spinner

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Item Added',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error adding item',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);

                // Display error in SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: `An error occurred: ${error.message}`,
                    timer: 5000,
                    showConfirmButton: true
                });
            });
        }

        // Update Item Function
        function updateItem() {
            const form = document.getElementById('edit-form');
            const formData = new FormData(form);
            const id = document.getElementById('edit-item-id').value;
            
            // Show SweetAlert loading spinner
            Swal.fire({
                title: 'Updating...',
                text: 'Please wait while we update the item.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`{{ url('/admin/inventory/update/') }}/${id}`, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Invalid JSON response');
                }

                return response.json();
            })
            .then(data => {
                Swal.close(); // Close the loading spinner

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Item Updated',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error updating item',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);

                // Display error in SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: `An error occurred: ${error.message}`,
                    timer: 5000,
                    showConfirmButton: true
                });
            });
        }

        // Confirm Delete Function
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading spinner
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait while we delete the item.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(`{{ url('/admin/inventory/delete/') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }

                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error('Invalid JSON response');
                        }

                        return response.json();
                    })
                    .then(data => {
                        Swal.close(); // Close the loading spinner

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Item Deleted',
                                timer: 3000,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Error deleting item',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        // Display error in SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: `An error occurred: ${error.message}`,
                            timer: 5000,
                            showConfirmButton: true
                        });
                    });
                }
            });
        }

        // Switch Tab Function
        function switchTab(tab) {
            document.getElementById('inventory-content').classList.remove('active');
            document.getElementById('stats-content').classList.remove('active');
            document.getElementById('inventory-tab').classList.remove('active');
            document.getElementById('stats-tab').classList.remove('active');

            if (tab === 'inventory') {
                document.getElementById('inventory-content').classList.add('active');
                document.getElementById('inventory-tab').classList.add('active');
            } else {
                document.getElementById('stats-content').classList.add('active');
                document.getElementById('stats-tab').classList.add('active');
            }
        }

        // Render the chart for inventory stats
        const ctx = document.getElementById('inventory-chart').getContext('2d');
        const inventoryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($inventoryStats['items']) !!}, 
                datasets: [{
                    label: 'Most Used Items',
                    data: {!! json_encode($inventoryStats['usage']) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Generate Inventory Report Function
// Generate Inventory Statistics Report with SweetAlert
function generateInventoryReport() {
    const form = document.getElementById('inventory-report-form');
    const formData = new FormData(form);

    // Front-end Validation
    const reportPeriod = formData.get('report_period');
    const reportDate = formData.get('report_date');

    if (!reportPeriod || !reportDate) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please select both report period and date.'
        });
        return;
    }

    // Confirmation Prompt
    Swal.fire({
        title: 'Generate Report',
        text: `Do you want to generate a ${capitalizeFirstLetter(reportPeriod)} report for ${reportDate}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, generate it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading spinner
            Swal.fire({
                title: 'Generating Report...',
                text: 'Please wait while your report is being generated.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send POST request to generate the report
            fetch('{{ route("admin.inventory.generateReport") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Unknown error');
                    });
                }
                return response.json();
            })
            .then(data => {
                Swal.close(); // Close the loading spinner

                if (data.success) {
                    // Automatically open the generated PDF in a new tab
                    window.open(data.pdf_url, '_blank');

                    // Notify the user of successful generation
                    Swal.fire({
                        icon: 'success',
                        title: 'Report Generated',
                        text: 'Your Inventory Statistics Report has been generated and opened in a new tab.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'An error occurred while generating the report.'
                    });
                }
            })
            .catch(error => {
                console.error('Error generating report:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'An unexpected error occurred while generating the report.'
                });
            });
        }
    });
}

// Helper function to capitalize the first letter
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}


        // Handle clicks outside the modal content to close it
        window.onclick = function(event) {
            const modal = document.getElementById('edit-modal');
            if (event.target == modal) {
                closeEditModal();
            }
        }

        // Close modal on 'Esc' key press
        document.addEventListener('keydown', function(event) {
            const modal = document.getElementById('edit-modal');
            if (event.key === 'Escape' && modal.classList.contains('active')) {
                closeEditModal();
            }
        });

        // Fade-Out Animation on Page Unload
        // window.onbeforeunload = function() {
        //     document.body.style.animation = 'fadeOut 1s forwards';
        // };
    </script>
</x-app-layout>
