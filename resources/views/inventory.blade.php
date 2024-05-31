<x-app-layout>
    <style>
        .container {
            display: flex;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 80px; /* Adjust margin to accommodate the sidebar */
            transition: margin-left 0.3s ease-in-out;
        }
        .sidebar:hover + .main-content {
            margin-left: 250px; /* Adjust margin when sidebar is expanded */
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #00b8e6;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #fff;
            color: black;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
        }
        .notification h2 {
            margin-top: 0;
        }
        .notification ul {
            list-style: none;
            padding: 0;
        }
        .notification ul li {
            margin-bottom: 10px;
            padding: 10px;
            background: #f1f1f1;
            border-radius: 5px;
        }
        .notification .close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
    </style>

    <div class="container">
        <x-sidebar />

        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Inventory Table Content -->
            <h1>Inventory</h1>
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Supplier</th>
                        <th>Date Acquired</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inventoryItems as $item)
                        <tr>
                            <td>{{ $item->item_id }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->supplier }}</td>
                            <td>{{ $item->date_acquired }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button onclick="document.getElementById('update-form-{{ $item->id }}').style.display='block'">Edit</button>
                                    <form method="POST" action="{{ route('inventory.delete', $item->id) }}">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                    </form>
                                </div>
                                <div id="update-form-{{ $item->id }}" style="display:none" class="form-container">
                                    <h2>Update Inventory Item</h2>
                                    <form method="POST" action="{{ route('inventory.update', $item->id) }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="item-id-{{ $item->id }}">Item ID</label>
                                            <input type="text" id="item-id-{{ $item->id }}" name="item_id" value="{{ $item->item_id }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="item-name-{{ $item->id }}">Item Name</label>
                                            <input type="text" id="item-name-{{ $item->id }}" name="item_name" value="{{ $item->item_name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="quantity-{{ $item->id }}">Quantity</label>
                                            <input type="number" id="quantity-{{ $item->id }}" name="quantity" value="{{ $item->quantity }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="supplier-{{ $item->id }}">Supplier</label>
                                            <input type="text" id="supplier-{{ $item->id }}" name="supplier" value="{{ $item->supplier }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="date-acquired-{{ $item->id }}">Date Acquired</label>
                                            <input type="date" id="date-acquired-{{ $item->id }}" name="date_acquired" value="{{ $item->date_acquired }}" required>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit">Update Item</button>
                                            <button type="button" onclick="document.getElementById('update-form-{{ $item->id }}').style.display='none'">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Add Inventory Item Form -->
            <div class="form-container">
                <h2>Add Inventory Item</h2>
                <form method="POST" action="{{ route('inventory.add') }}">
                    @csrf
                    <div class="form-group">
                        <label for="item-id">Item ID</label>
                        <input type="text" id="item-id" name="item_id" required>
                    </div>
                    <div class="form-group">
                        <label for="item-name">Item Name</label>
                        <input type="text" id="item-name" name="item_name" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="supplier">Supplier</label>
                        <input type="text" id="supplier" name="supplier" required>
                    </div>
                    <div class="form-group">
                        <label for="date-acquired">Date Acquired</label>
                        <input type="date" id="date-acquired" name="date_acquired" required>
                    </div>
                    <div class="form-group">
                        <button type="submit">Add Item</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notification = document.getElementById('notification');
            notification.style.display = 'block';
        });

        function closeNotification() {
            const notification = document.getElementById('notification');
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 300);
        }
    </script>
</x-app-layout>
