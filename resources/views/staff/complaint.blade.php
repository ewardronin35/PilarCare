<x-app-layout>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            animation: fadeInBackground 1s ease-in-out;
        }

        .main-content {
            margin-top: 40px;
        }

        .complaints-section {
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .complaints-section h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .complaints-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .complaints-table th,
        .complaints-table td {
            padding: 15px;
            text-align: left;
        }

        .complaints-table th {
            background-color: #f5f5f5;
            color: #333;
            font-weight: 600;
            border-bottom: 1px solid #ddd;
        }

        .complaints-table td {
            border-bottom: 1px solid #eee;
        }

        .complaints-table td:last-child {
            text-align: center;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .preview-button,
        .edit-button {
            background-color: #00d1ff;
            color: white;
            padding: 5px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .preview-button:hover,
        .edit-button:hover {
            background-color: #00b8e6;
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            animation: fadeIn 0.5s ease-in-out;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 500px;
            border-radius: 10px;
            animation: slideIn 0.5s ease-in-out;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-body {
            margin-top: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group textarea {
            resize: vertical;
        }

        .save-button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .save-button:hover {
            background-color: #218838;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Complaints Section -->
        <div class="complaints-section">
            <h2>Staff Complaint History</h2>

            <!-- Complaints Table -->
            <table class="complaints-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Health History</th>
                        <th>Description of Sickness</th>
                        <th>Pain Assessment</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($complaints as $complaint)
                        <tr>
                        <td>{{ $complaint->first_name }} {{ $complaint->last_name }}</td>
                        <td>{{ $complaint->health_history }}</td>
                            <td>{{ $complaint->sickness_description }}</td>
                            <td>{{ $complaint->pain_assessment }}</td>
                            <td>{{ $complaint->status }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="preview-button" onclick="openPreviewModal({{ $complaint->id }})">Preview</button>
                                    <button class="edit-button" onclick="openEditModal({{ $complaint->id }})">Edit</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="preview-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Complaint Details</h2>
                <span class="close" onclick="closeModal('preview-modal')">&times;</span>
            </div>
            <div class="modal-body" id="preview-modal-body">
                <!-- Complaint details will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Complaint</h2>
                <span class="close" onclick="closeModal('edit-modal')">&times;</span>
            </div>
            <form id="edit-complaint-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" id="edit-modal-body">
                    <!-- Editable complaint details will be loaded here -->
                </div>
                <div class="form-group">
                    <button type="submit" class="save-button">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // Open modal for previewing complaint details
        function openPreviewModal(complaintId) {
    fetch(`/staff/complaint/${complaintId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const modalBody = document.getElementById('preview-modal-body');
            modalBody.innerHTML = `
                <p><strong>Name:</strong> ${data.name}</p>
                <p><strong>Health History:</strong> ${data.health_history}</p>
                <p><strong>Description of Sickness:</strong> ${data.sickness_description}</p>
                <p><strong>Pain Assessment:</strong> ${data.pain_assessment}</p>
                <p><strong>Status:</strong> ${data.status}</p>
            `;
            document.getElementById('preview-modal').style.display = 'block';
        })
        .catch(error => {
            console.error('Error fetching complaint details:', error);
        });
}

        // Open modal for editing complaint details
        function openEditModal(complaintId) {
            fetch(`/staff/complaint/${complaintId}/edit`)
                .then(response => response.json())
                .then(data => {
                    const editBody = document.getElementById('edit-modal-body');
                    editBody.innerHTML = `
                        <div class="form-group">
                            <label for="notes">Notes:</label>
                            <textarea id="notes" name="notes">${data.notes}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select id="status" name="status">
                                <option value="pending" ${data.status === 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="resolved" ${data.status === 'resolved' ? 'selected' : ''}>Resolved</option>
                            </select>
                        </div>
                    `;
                    document.getElementById('edit-complaint-form').action = `/staff/complaint/${complaintId}`;
                    document.getElementById('edit-modal').style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        }

        // Close modal function
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
</x-app-layout>
