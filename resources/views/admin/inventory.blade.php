<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .main-content {
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
            margin-top: 30px;
        }

        .form-container, .table-container, .chart-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.8s ease-in-out;
        }

        .form-container {
            width: 100%;
            max-width: 450px;
            animation: slideInLeft 0.6s ease-in-out;
        }

        .table-container {
            flex: 1;
            max-height: 400px;
            overflow-y: scroll;
            animation: slideInRight 0.6s ease-in-out;
        }

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

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 1rem;
            margin-bottom: 5px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1rem;
        }

        .form-group button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }
        .tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
            justify-content: space-around;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 10px 10px 0 0;
        }

        .tab:hover {
            background-color: #c9d1d9;
        }

        .tab.active {
            background-color: #007bff;
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
        .action-buttons button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1rem;
        }

        .action-buttons button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .action-buttons button:active {
            transform: scale(0.95);
        }

        h1 {
            font-weight: 600;
            text-align: center;
        }

        p {
            font-weight: 400;
        }

        .form-group input:hover, .form-group select:hover {
            border-color: #00d1ff;
            transition: border-color 0.3s;
        }

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
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        }

        .modal-body {
            padding: 20px 0;
        }

        .modal-footer {
            text-align: right;
        }

        .modal-footer button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        .modal-footer button:hover {
            background-color: #0056b3;
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
                            <button type="button" onclick="addItem()">Add Item</button>
                            <button type="button" onclick="clearForm()">Clear Form</button>
                        </div>
                    </form>
                </div>

                <!-- Inventory Table -->
                <div class="table-container">
                    <h2><i class="fas fa-table"></i> Inventory Table</h2>
                    <table class="inventory-table">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Brand</th>
                                <th>Type</th>
                                <th>Date Acquired</th>
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
                                    <td>{{ $item->date_acquired }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button onclick="openEditModal({{ $item->id }})"><i class="fas fa-edit"></i> Edit</button>
                                            <button onclick="confirmDelete({{ $item->id }})"><i class="fas fa-trash"></i> Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Edit Modal HTML Structure -->
        <div id="edit-modal" class="modal">
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
                </form>
            </div>
            <div class="modal-footer">
                <button onclick="closeEditModal()">Close</button>
                <button type="button" onclick="updateItem()">Update</button>
            </div>
        </div>

        <!-- Visualization Section -->
        <div id="stats-content" class="tab-content">
            <div class="chart-container">
                <h2><i class="fas fa-chart-line"></i> Inventory Statistics</h2>
                <canvas id="inventory-chart" style="max-width: 100%;"></canvas>
            </div>
        </div>
    </main>

    <!-- Script Section -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function clearForm() {
            document.getElementById('add-form').reset();
        }

        function openEditModal(id) {
            const item = document.getElementById(`item-row-${id}`);
            if (!item) return;

            document.getElementById('edit-item-id').value = id;
            document.getElementById('edit-item-name').value = item.children[0].innerText;
            document.getElementById('edit-quantity').value = item.children[1].innerText;
            document.getElementById('edit-supplier').value = item.children[2].innerText;
            document.getElementById('edit-type').value = item.children[3].innerText;
            document.getElementById('edit-date-acquired').value = formatDateForInput(item.children[4].innerText);

            document.getElementById('edit-modal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('edit-modal').style.display = 'none';
        }

        function formatDateForInput(dateString) {
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

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
        // Check if the response is OK (status code 200-299)
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        // Check if the response is valid JSON
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

        function updateItem() {
            const form = document.getElementById('edit-form');
            const formData = new FormData(form);
            const id = document.getElementById('edit-item-id').value;
            
            fetch(`{{ url('/admin/inventory/update/') }}/${id}`, {
                method: 'POST',
                body: formData
            }).then(response => response.json())
            .then(data => {
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
                        text: 'Error updating item',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            }).catch(error => console.error('Error:', error));
        }

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
                    fetch(`{{ url('/admin/inventory/delete/') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(response => response.json())
                    .then(data => {
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
                                text: 'Error deleting item',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    }).catch(error => console.error('Error:', error));
                }
            });
        }

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
    </script>
</x-app-layout>
