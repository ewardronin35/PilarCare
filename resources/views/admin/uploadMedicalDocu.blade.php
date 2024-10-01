<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome for Icons -->
    <style>
        /* General Styles */
        body {
            background-color: #f5f7fa;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            display: flex;
           
         
        }

        .tab-buttons {
            display: flex;
            margin-bottom: 20px;
        }

        .tab-buttons button {
            flex: 1;
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            font-size: 16px;
            font-weight: 600;
            border-radius: 5px;
            margin-right: 10px;
        }

        .tab-buttons button:last-child {
            margin-right: 0;
        }

        .tab-buttons button:hover {
            background-color: #0056b3;
            transform: scale(1.02);
        }

        .tab-buttons button.active {
            background-color: #0056b3;
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        table th, table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
        }

        table th {
            background-color: #f1f1f1;
            font-weight: bold;
            color: #333;
        }

        table tr:nth-child(even) {
            background-color: #fafafa;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        /* Action Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
            margin-right: 5px;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow-y: auto;
            background-color: rgba(0, 0, 0, 0.6);
            animation: fadeIn 0.3s;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #888;
            width: 90%;
            max-width: 800px;
            border-radius: 10px;
            position: relative;
            animation: slideDown 0.3s;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 20px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
        }

        .modal-header {
            margin-bottom: 20px;
        }

        .modal-header h2 {
            margin: 0;
            color: #333;
        }

        .modal-body p {
            margin: 10px 0;
            color: #555;
        }

        .modal-body a {
            color: #007bff;
            text-decoration: none;
        }

        .modal-body a:hover {
            text-decoration: underline;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .tab-buttons button {
                font-size: 14px;
                padding: 10px;
            }

            table th, table td {
                padding: 10px 12px;
            }

            .modal-content {
                padding: 20px;
            }

            .modal-body p {
                font-size: 14px;
            }

            .btn {
                padding: 6px 10px;
                font-size: 12px;
            }
        }

        /* Badge Styles for Medicines */
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 12px;
            margin: 2px;
        }

        .badge-primary {
            background-color: #007bff;
            color: white;
        }

        /* Information Card Styles */
        .info-card {
            background-color: #e9f7ef;
            padding: 15px;
            border-left: 5px solid #28a745;
            border-radius: 5px;
            margin-bottom: 15px;
            color: #155724;
        }

        .info-card strong {
            font-weight: bold;
        }
    </style>

    <div class="container">
        <main class="main-content">
            <!-- Tab Buttons -->
            <div class="tab-buttons">
                <button id="pending-approvals-tab" class="active" onclick="showTab('pending-approvals')">
                    <i class="fas fa-user-clock"></i> Pending Approvals
                </button>
                <!-- Add more tabs if needed -->
            </div>

            <!-- Tab Content -->
            <div id="pending-approvals" class="tab-content active">
                <h1>Pending Medical Record Approvals</h1>
                <div class="table-container">
                    @if(isset($pendingMedicalRecords) && $pendingMedicalRecords->isNotEmpty())
                        <table>
                            <thead>
                                <tr>
                                    <th>Record Date</th>
                                    <th>Name</th>
                                    <th>Birthdate</th>
                                    <th>Age</th>
                                    <th>Address</th>
                                    <th>Contact Number</th>
                                    <th>Profile Picture</th>
                                    <th>Medicines</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingMedicalRecords as $medicalRecord)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($medicalRecord->record_date)->format('Y-m-d') }}</td>
                                        <td>{{ $medicalRecord->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($medicalRecord->birthdate)->format('Y-m-d') }}</td>
                                        <td>{{ $medicalRecord->age }}</td>
                                        <td>{{ $medicalRecord->address }}</td>
                                        <td>{{ $medicalRecord->personal_contact_number }}</td>
                                        <td>
                                            <div class="image-container">
                                                <img src="{{ asset('storage/' . $medicalRecord->profile_picture) }}" alt="Profile Picture" onclick="openModal('{{ asset('storage/' . $medicalRecord->profile_picture) }}')">
                                            </div>
                                        </td>
                                        <td>
                                            @if(isset($medicalRecord->medicines))
                                                @php
                                                    $medicines = json_decode($medicalRecord->medicines, true);
                                                @endphp
                                                @foreach($medicines as $medicine)
                                                    <span class="badge badge-primary">{{ $medicine }}</span>
                                                @endforeach
                                            @else
                                                <span class="badge badge-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-success" onclick="approveRecord({{ $medicalRecord->id }})">
                                                <i class="fas fa-check-circle"></i> Approve
                                            </button>
                                            <button type="button" class="btn btn-danger" onclick="rejectRecord({{ $medicalRecord->id }})">
                                                <i class="fas fa-times-circle"></i> Reject
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination (if applicable) -->
                    @else
                        <!-- Display a friendly message with a modal trigger -->
                        <div class="info-card">
                            <strong>No Pending Medical Records</strong>
                            <p>There are currently no pending medical records awaiting approval.</p>
                        </div>
                        <button class="btn btn-primary" onclick="showNoRecordAlert('No Pending Records', 'There are no pending medical records at this time.')">
                            <i class="fas fa-info-circle"></i> View Details
                        </button>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <!-- Modal for Image Preview -->
    <div id="previewModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img id="modal-image" src="" alt="Image Preview">
        </div>
    </div>

    <!-- Modal for No Records (Optional) -->
    <div id="noRecordModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeNoRecordModal()">&times;</span>
            <div class="modal-header">
                <h2 id="noRecordModalLabel">No Records Found</h2>
            </div>
            <div class="modal-body">
                <p id="noRecordModalMessage">There are no records available at this time.</p>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Function to switch between tabs
        function showTab(tabId) {
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(tabContent => {
                tabContent.classList.remove('active');
            });

            const selectedTabContent = document.getElementById(tabId);
            selectedTabContent.classList.add('active');

            const tabButtons = document.querySelectorAll('.tab-buttons button');
            tabButtons.forEach(button => {
                button.classList.remove('active');
            });

            document.getElementById(tabId + '-tab').classList.add('active');
        }

        // Function to open image modal
        function openModal(src) {
            const modal = document.getElementById('previewModal');
            const modalImg = document.getElementById('modal-image');
            modalImg.src = src;
            modal.style.display = 'block';
        }

        // Function to close image modal
        function closeModal() {
            const modal = document.getElementById('previewModal');
            modal.style.display = 'none';
        }

        // Function to approve medical record
        function approveRecord(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to approve this medical record!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/medical-record/${id}/approve`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Approved!',
                                'The medical record has been approved.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'The medical record could not be approved.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'An unexpected error occurred while approving the record.',
                            'error'
                        );
                    });
                }
            });
        }

        // Function to reject medical record
        function rejectRecord(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to reject and delete this medical record!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/medical-record/${id}/reject`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Rejected!',
                                'The medical record has been rejected and deleted.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'The medical record could not be rejected.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'An unexpected error occurred while rejecting the record.',
                            'error'
                        );
                    });
                }
            });
        }

        // Function to show no record alert using SweetAlert2
        function showNoRecordAlert(title, message) {
            Swal.fire({
                icon: 'info',
                title: title,
                text: message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#007bff'
            });
        }

        // Function to close no record modal
        function closeNoRecordModal() {
            const modal = document.getElementById('noRecordModal');
            modal.style.display = 'none';
        }

        // Event listener to close modals when clicking outside the modal content
        window.onclick = function(event) {
            const previewModal = document.getElementById('previewModal');
            const noRecordModal = document.getElementById('noRecordModal');
            if (event.target == previewModal) {
                previewModal.style.display = 'none';
            }
            if (event.target == noRecordModal) {
                noRecordModal.style.display = 'none';
            }
        }
    </script>
</x-app-layout>
