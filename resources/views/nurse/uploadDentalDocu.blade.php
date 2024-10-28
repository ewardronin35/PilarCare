<x-app-layout :pageTitle="' Dental Approval'">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
<head>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert2 for Alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
    <style>
        /* General Styling */
        body {
            background-color: #f5f7fa;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        .main-content {
            margin-top: 30px;
        }

        /* Search Bar Styling */
        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-bar input {
            width: 300px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px 0 0 5px;
            outline: none;
            font-size: 1rem;
        }

        .search-bar button {
            padding: 10px 15px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1rem;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }

        /* Tab Navigation Styling */
        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            flex-wrap: wrap;
            gap: 10px;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            color: #555;
            transition: color 0.3s, border-bottom 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab-btn:hover {
            color: #007bff;
        }

        .tab-btn.active {
            color: #007bff;
            border-bottom: 3px solid #007bff;
        }

        /* Table Container */
        .table-container {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            background-color: #fff;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px; /* Ensure table has a minimum width */
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: center;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: 600;
            color: #333;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        table tbody tr:hover {
            background-color: #f9f9f9;
            cursor: pointer;
        }

        /* Action Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s, transform 0.3s;
            color: white;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        /* Modal Styling */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            animation: fadeIn 0.3s ease-in-out;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 800px;
            border-radius: 10px;
            animation: slideIn 0.3s ease-in-out;
            position: relative;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.8rem;
            color: #007bff;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
        }

        .modal-body p {
            margin: 10px 0;
            color: #555;
            font-size: 1rem;
        }

        /* Image Previews */
        .image-previews img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 5px 5px 0;
            transition: transform 0.3s;
        }

        .image-previews img:hover {
            transform: scale(1.05);
        }

        /* Spinner Overlay */
        #spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #007bff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Animations */
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .tabs {
                flex-direction: column;
                align-items: center;
            }

            .tab-btn {
                width: 100%;
                text-align: center;
                border-bottom: 1px solid #ddd;
            }

            .tab-btn:last-child {
                border-bottom: none;
            }

            .table-container {
                max-height: 300px;
            }

            table {
                min-width: 600px;
            }

            .image-previews img {
                width: 80px;
                height: 80px;
            }

            .btn {
                padding: 6px 10px;
                font-size: 0.8rem;
            }
        }
    </style>

    <div class="main-content">
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search Bar -->
       
<h2> Teeth Approvals</h2>
        <!-- Tab Navigation -->
  

        <!-- Dental Records Table -->
        <div class="table-container">
      <!-- Table Headers -->
<table id="dental-records-table">
    <thead>
        <tr>
            <th>Patient Name</th>
            <th>User Type</th>
            <th>Tooth Number</th>
            <th>Status</th>
            <th>Notes</th>
            <th>Teeth Images</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($teethData as $tooth)
            <tr>
                <td>{{ $tooth['patient_name'] }}</td>
                <td>{{ $tooth['user_type'] }}</td>
                <td>{{ $tooth['tooth_number'] }}</td> <!-- Corrected -->
                <td>{{ $tooth['status'] }}</td>
                <td>{{ $tooth['notes'] }}</td>
                <td>
    @php
        // Ensure dental_pictures is an array
        $pictures = is_array($tooth['dental_pictures']) ? $tooth['dental_pictures'] : json_decode($tooth['dental_pictures'], true) ?? [];
    @endphp

    @if(count($pictures) > 0)
        <div class="image-previews">
        @foreach($pictures as $picture)
    <img src="{{ $picture }}" alt="Tooth Image" onclick="openImageModal('{{ $picture }}')" />
@endforeach

        </div>
    @else
        No images
    @endif
</td>

                <td>
                    <button type="button" class="btn btn-success" onclick="approveRecord({{ $tooth['id'] }})">
                        <i class="fas fa-check"></i> Approve
                    </button>
                    <button type="button" class="btn btn-danger" onclick="rejectRecord({{ $tooth['id'] }})">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #888;">No pending dental records available.</td>
            </tr>
        @endforelse
    </tbody>
</table>

        </div>
    </div>

    <!-- Image Preview Modal -->
    <div id="image-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeImageModal()">&times;</span>
            <img id="modal-image" src="" alt="Image Preview" style="width: 100%; height: auto; border-radius: 5px;">
        </div>
    </div>

    <!-- Spinner Overlay -->
    <div id="spinner-overlay">
        <div class="spinner"></div>
    </div>

    <script>
        // CSRF Token Setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    


        // Fetch dental records based on selected role
        
    function fetchDentalRecords(role) {
        const tableBody = document.querySelector('#dental-records-table tbody');
        tableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #555;">Loading...</td></tr>';
        showSpinner();

        fetch(`/nurse/dental-records?role=${encodeURIComponent(role)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            hideSpinner();
            if (!response.ok) {
                throw new Error(`Server responded with status ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            tableBody.innerHTML = ''; // Clear table

            if (data.length > 0) {
                data.forEach(tooth => {
                    let imagesHtml = '';
                    if (tooth.dental_pictures && tooth.dental_pictures.length > 0) {
                        imagesHtml = `<div class="image-previews">`;
                        tooth.dental_pictures.forEach(picture => {
                            imagesHtml += `<img src="${picture}" alt="Tooth Image" onclick="openImageModal('${picture}')" />`;
                        });
                        imagesHtml += `</div>`;
                    } else {
                        imagesHtml = 'No images';
                    }

                    tableBody.innerHTML += `
                        <tr>
                            <td>${escapeHtml(tooth.patient_name || 'N/A')}</td>
                            <td>${escapeHtml(tooth.user_type || 'N/A')}</td>
                            <td>${escapeHtml(tooth.tooth_number || 'N/A')}</td>
                            <td>${escapeHtml(tooth.status || 'N/A')}</td>
                            <td>${escapeHtml(tooth.notes || 'N/A')}</td>
                            <td>${imagesHtml}</td>
                            <td>
                                <button type="button" class="btn btn-success" onclick="approveRecord(${tooth.id})">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button type="button" class="btn btn-danger" onclick="rejectRecord(${tooth.id})">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; color: #888;">No records found for this role.</td>
                    </tr>
                `;
            }

            // Initialize DataTable after a slight delay to stabilize DOM
            setTimeout(() => {
                $('#dental-records-table').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: true,
                    responsive: true,
                    autoWidth: false,
                    destroy: true // Ensures any previous instance is cleared
                });
            }, 200); // Adjust delay as needed
        })
        .catch(error => {
            hideSpinner();
            console.error('Error fetching dental records:', error);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" style="text-align: center; color: #dc3545;">Failed to load dental records. Please try again later.</td>
                </tr>
            `;
        });
    }

        // Function to open image modal
        function openImageModal(src) {
            const modal = document.getElementById('image-modal');
            const modalImg = document.getElementById('modal-image');
            modalImg.src = src;
            modal.style.display = 'flex';
        }

        // Function to close image modal
        function closeImageModal() {
            const modal = document.getElementById('image-modal');
            modal.style.display = 'none';
        }

        // Function to approve a dental record
        function approveRecord(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to approve this dental record!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showSpinner();
                    fetch(`/nurse/dental-record/${id}/approve`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideSpinner();
                        if (data.success) {
                            Swal.fire(
                                'Approved!',
                                data.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'The dental record could not be approved.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        hideSpinner();
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

        // Function to reject a dental record
        function rejectRecord(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to reject this dental record!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showSpinner();
                    fetch(`/nurse/dental-record/${id}/reject`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideSpinner();
                        if (data.success) {
                            Swal.fire(
                                'Rejected!',
                                data.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'The dental record could not be rejected.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        hideSpinner();
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

        // Function to switch tabs
     
      

        // Helper Function to Escape HTML (Prevent XSS)
        function escapeHtml(text) {
    if (text === null || text === undefined) return '';
    text = text.toString(); // Convert to string
    return text.replace(/&/g, "&amp;")
               .replace(/</g, "&lt;")
               .replace(/>/g, "&gt;")
               .replace(/"/g, "&quot;")
               .replace(/'/g, "&#039;");
}


        // Spinner Functions
        function showSpinner() {
            document.getElementById('spinner-overlay').style.display = 'flex';
        }

        function hideSpinner() {
            document.getElementById('spinner-overlay').style.display = 'none';
        }

        // Initialize with default tab and fetch records
        document.addEventListener('DOMContentLoaded', function () {
        // Load the default records and then initialize DataTables
        const defaultRole = 'student';
        fetchDentalRecords(defaultRole);
        switchTab('student'); // Load 'student' records by default

        // Initialize DataTables after table content is fully loaded
        setTimeout(() => {
            $('#dental-records-table').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                responsive: true,
                autoWidth: false,
                destroy: true // Ensures any previous instances are cleared
            });
        }, 500); // Add a delay to allow content to load; adjust as needed
    });

        // Close modal when clicking outside of the modal content
        window.onclick = function(event) {
            const imageModal = document.getElementById('image-modal');
            if (event.target == imageModal) {
                imageModal.style.display = 'none';
            }
        }
        function refreshDentalRecords() {
        const activeTab = document.querySelector('.tab-btn.active');
        const role = activeTab ? activeTab.getAttribute('data-role') : 'student';
        const searchQuery = document.getElementById('search-input').value.trim();

        fetch(`/nurse/dental-records?role=${encodeURIComponent(role)}&search=${encodeURIComponent(searchQuery)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Server responded with status ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const tableBody = document.querySelector('#dental-records-table tbody');
            tableBody.innerHTML = '';

            if (data.length > 0) {
                data.forEach(tooth => {
                    let imagesHtml = '';
                    if (tooth.dental_pictures && tooth.dental_pictures.length > 0) {
                        imagesHtml = `<div class="image-previews">`;
                        tooth.dental_pictures.forEach(picture => {
                            imagesHtml += `<img src="${picture}" alt="Tooth Image" onclick="openImageModal('${picture}')"/>`;
                        });
                        imagesHtml += `</div>`;
                    } else {
                        imagesHtml = 'No images';
                    }

                    tableBody.innerHTML += `
                        <tr>
                            <td>${escapeHtml(tooth.patient_name || 'N/A')}</td>
                            <td>${escapeHtml(tooth.user_type || 'N/A')}</td>
                            <td>${escapeHtml(tooth.tooth_number || 'N/A')}</td>
                            <td>${escapeHtml(tooth.status || 'N/A')}</td>
                            <td>${escapeHtml(tooth.notes || 'N/A')}</td>
                            <td>${imagesHtml}</td>
                            <td>
                                <button type="button" class="btn btn-success" onclick="approveRecord(${tooth.id})">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button type="button" class="btn btn-danger" onclick="rejectRecord(${tooth.id})">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; color: #888;">No pending dental records found.</td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching dental records:', error);
            // Optionally, display an error message to the user
        });
    }

    // Set an interval to refresh dental records every 30 seconds
    setInterval(refreshDentalRecords, 30000); // 30000 milliseconds = 30 seconds


    </script>
</x-app-layout>
