<x-app-layout>
    <style>
        .container {
            display: flex;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-top: 30px;
            margin-left: 80px; /* Adjust margin to accommodate the sidebar */
            transition: margin-left 0.3s ease-in-out;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info .username {
            margin-right: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .tab:hover {
            background-color: #f0f0f0;
        }

        .tab.active {
            border-bottom: 2px solid #007bff;
            font-weight: bold;
            color: #007bff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .inventory-table th,
        .inventory-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .inventory-table th {
            background-color: #00d1ff;
            color: white;
        }

        .form-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 1rem;
        }

        .form-group input,
        .form-group select {
            width: 98%;
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

        .form-group button:active {
            transform: scale(0.95);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .action-buttons {
            display: flex;
            gap: 10px;
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

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>



        <main class="main-content">
            <h1>Inventory</h1>

            <!-- Tabs -->
            <div class="tabs">
                <div class="tab active" onclick="showTab('add-item')">Add Inventory Item</div>
                <div class="tab" onclick="showTab('inventory-table')">Inventory Table</div>
            </div>

            <!-- Add Inventory Item Form -->
            <div id="add-item" class="tab-content active">
                <div class="form-container">
                    <h2>Add Inventory Item</h2>
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
                        </div>
                    </form>
                </div>
            </div>

            <!-- Inventory Table Content -->
            <div id="inventory-table" class="tab-content">
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>Item ID</th>
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
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->supplier }}</td>
                                <td>{{ $item->type }}</td>
                                <td>{{ $item->date_acquired }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button onclick="openEditModal({{ $item->id }})">Edit</button>
                                        <button onclick="confirmDelete({{ $item->id }})">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Edit Inventory Item Modal -->
            <div id="edit-modal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeEditModal()">&times;</span>
                    <h2>Edit Inventory Item</h2>
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
                            <button type="button" onclick="updateItem()">Update Item</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showTab(tabId) {
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(tabContent => {
                tabContent.classList.remove('active');
            });

            const selectedTabContent = document.getElementById(tabId);
            selectedTabContent.classList.add('active');

            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            document.querySelector(`.tab[onclick="showTab('${tabId}')"]`).classList.add('active');
        }

        function closeNotification() {
            const notification = document.getElementById('notification');
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 300);
        }

        function showNotification(message) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
        }

        function openEditModal(id) {
            const item = document.getElementById(`item-row-${id}`);
            document.getElementById('edit-item-id').value = id;
            document.getElementById('edit-item-name').value = item.children[1].innerText;
            document.getElementById('edit-quantity').value = item.children[2].innerText;
            document.getElementById('edit-supplier').value = item.children[3].innerText;
            document.getElementById('edit-type').value = item.children[4].innerText;
            document.getElementById('edit-date-acquired').value = item.children[5].innerText;
            document.getElementById('edit-modal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('edit-modal').style.display = 'none';
        }

        function addItem() {
            const form = document.getElementById('add-form');
            const formData = new FormData(form);
            fetch('{{ route("admin.inventory.add") }}', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.success);
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error adding item',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            }).catch(error => {
                console.error('Error:', error);
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
                    showNotification(data.success);
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error updating item',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            }).catch(error => {
                console.error('Error:', error);
            });
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
                            document.getElementById(`item-row-${id}`).remove();
                            showNotification('Item deleted successfully');
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error deleting item',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        }
    </script>
</x-app-layout>
