<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }
        .main-content {
            margin-top: 30px;
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
        }
        .form-container, .table-container {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
        }
        .tab-buttons {
            margin-bottom: 20px;
        }
        .tab-buttons button {
            background-color: #00d2ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 16px;
            font-weight: bold;
        }
        .tab-buttons button:hover {
            background-color: #00b8e6;
        }
        .tab-buttons button:active {
            transform: scale(0.95);
        }
        .tab-buttons button.active {
            background-color: #00a8cc;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
            animation: fadeInUp 0.5s ease-in-out;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            animation: fadeIn 1s ease-in-out;
            text-align: center;
        }
        table th {
            background-color: #f2f2f2;
        }
        .image-previews img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
        }
        .image-previews {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .image-container {
            width: 100px;
            height: 100px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }
        .image-container img {
            max-width: 100%;
            max-height: 100%;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn-success {
            background-color: #28a745;
            color: white;
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
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            animation: fadeIn 0.5s ease-in-out;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 800px;
            border-radius: 10px;
            animation: fadeInUp 0.5s ease-in-out;
            overflow: hidden;
            text-align: center;
        }
        .modal img {
            max-width: 100%;
            max-height: 70vh;
            object-fit: contain;
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
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
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
    </style>

    <div class="container">
        <main class="main-content">
            <div class="tab-buttons">
                <button id="pending-approvals-tab" class="active" onclick="showTab('pending-approvals')">Pending Medical Record Approvals</button>
            </div>

            <!-- Pending Medical Records Content -->
            <div id="pending-approvals" class="tab-content active">
                <h1>Pending Medical Record Approvals</h1>
                <div class="table-container">
                    @if(isset($pendingMedicalRecords) && $pendingMedicalRecords->isNotEmpty())
                        <table>
                            <thead>
                                <tr>
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
                                        <td>{{ $medicalRecord->name }}</td>
                                        <td>{{ $medicalRecord->birthdate }}</td>
                                        <td>{{ $medicalRecord->age }}</td>
                                        <td>{{ $medicalRecord->address }}</td>
                                        <td>{{ $medicalRecord->personal_contact_number }}</td>
                                        <td>
                                            <div class="image-container">
                                                <img src="{{ asset('storage/' . $medicalRecord->profile_picture) }}" alt="Profile Picture" onclick="openModal('{{ asset('storage/' . $medicalRecord->profile_picture) }}')">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="image-previews">
                                                @foreach(json_decode($medicalRecord->medicines) as $medicine)
                                                    <p>{{ $medicine }}</p>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                        <button type="button" class="btn btn-success" onclick="approveRecord({{ $medicalRecord->id }})">
                                        <img src="https://img.icons8.com/material-rounded/24/ffffff/checkmark--v1.png"/> Approve
                                            </button>
                                            <button type="button" class="btn btn-danger" onclick="rejectRecord({{ $medicalRecord->id }})">
                                                <img src="https://img.icons8.com/material-rounded/24/ffffff/delete-sign.png"/> Reject
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No pending medical records available.</p>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <div id="previewModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img id="modal-image" src="" alt="Image Preview">
        </div>
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

            const tabButtons = document.querySelectorAll('.tab-buttons button');
            tabButtons.forEach(button => {
                button.classList.remove('active');
            });

            document.getElementById(tabId + '-tab').classList.add('active');
        }

        function openModal(src) {
            const modal = document.getElementById('previewModal');
            const modalImg = document.getElementById('modal-image');
            modalImg.src = src;
            modal.style.display = 'block';
        }

        function closeModal() {
            const modal = document.getElementById('previewModal');
            modal.style.display = 'none';
        }

        function approveRecord(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to approve this medical record!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/medical-record/${id}/approve`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).then(response => {
                        if (response.ok) {
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
                                'The medical record could not be approved.',
                                'error'
                            );
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'There was a problem with the approval process.',
                            'error'
                        );
                    });
                }
            });
        }

        function rejectRecord(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to reject this medical record!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/medical-record/${id}/reject`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire(
                                'Rejected!',
                                'The medical record has been rejected.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'The medical record could not be rejected.',
                                'error'
                            );
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'There was a problem with the rejection process.',
                            'error'
                        );
                    });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            showTab('pending-approvals');
        });
    </script>
</x-app-layout>
