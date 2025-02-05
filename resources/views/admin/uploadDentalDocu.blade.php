    <x-app-layout :pageTitle="'Dental Approval'">   
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    <head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
    
            <div class="bulk-actions" style="margin-bottom: 15px;">
        <button id="bulk-approve-btn" class="btn btn-success">
            <i class="fas fa-check-circle"></i> Bulk Approve
        </button>
        <button id="bulk-reject-btn" class="btn btn-danger">
            <i class="fas fa-times-circle"></i> Bulk Reject
        </button>
    </div>

            <!-- Dental Records Table -->
            <div class="table-container">
        <!-- Table Headers -->
    <table id="dental-records-table">
        <thead>
            <tr>
            <th><input type="checkbox" id="select-all-checkbox"></th>

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
        $(document).ready(function () {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Initialize DataTable with AJAX
            const table = $('#dental-records-table').DataTable({
                processing: true,
                serverSide: false, // Set to true if implementing server-side processing
                ajax: {
                    url: '/admin/dental-records', // Your AJAX endpoint
                    type: 'GET',
                    data: function (d) {
                        d.role = 'student'; // Adjust as needed or make dynamic
                    },
                    dataSrc: function (json) {
                        return json; // Adjust based on your server response
                    }
                },
                columns: [
                    { 
                        data: 'id',
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="select-record" data-record-id="${data}">`;
                        },
                        orderable: false
                    },
                    { data: 'patient_name' },
                    { data: 'user_type' },
                    { data: 'tooth_number' },
                    { data: 'status' },
                    { data: 'notes' },
                    { 
                        data: 'dental_pictures',
                        render: function(data, type, row) {
                            if (data && data.length > 0) {
                                let imagesHtml = '<div class="image-previews">';
                                data.forEach(picture => {
                                    imagesHtml += `<img src="${picture}" alt="Tooth Image" onclick="openImageModal('${picture}')" />`;
                                });
                                imagesHtml += '</div>';
                                return imagesHtml;
                            } else {
                                return 'No images';
                            }
                        },
                        orderable: false
                    },
                    { 
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                                <button type="button" class="btn btn-success" onclick="approveRecord(${data})">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button type="button" class="btn btn-danger" onclick="rejectRecord(${data})">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            `;
                        },
                        orderable: false
                    }
                ],
                // Optional: Add any additional DataTables options here
            });

            // Search Functionality
       
            // Select All Checkbox Handler
            $('#select-all-checkbox').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.select-record').prop('checked', isChecked);
            });

            // Update Select All checkbox based on individual selections
            $(document).on('change', '.select-record', function() {
                if (!$(this).is(':checked')) {
                    $('#select-all-checkbox').prop('checked', false);
                } else if ($('.select-record:checked').length === $('.select-record').length) {
                    $('#select-all-checkbox').prop('checked', true);
                }
            });

            // Bulk Approve Button Click Handler
            $('#bulk-approve-btn').on('click', function() {
                const selectedRecords = [];
                $('.select-record:checked').each(function() {
                    selectedRecords.push($(this).data('record-id'));
                });

                if (selectedRecords.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Selection',
                        text: 'Please select at least one dental record to approve.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Bulk Approve',
                    text: `Are you sure you want to approve ${selectedRecords.length} dental record(s)?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, approve them!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showSpinner();

                        // Send AJAX request for bulk approval
                        $.ajax({
                            url: '/admin/dental-records/bulk-approve', // Update with your route
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            data: {
                                record_ids: selectedRecords
                            },
                            success: function(data) {
                                hideSpinner();
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Approved!',
                                        text: data.message || `${data.approved_count} dental record(s) approved successfully.`,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    // Reload DataTables to reflect changes
                                    table.ajax.reload();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: data.message || 'Failed to approve some dental records.',
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                hideSpinner();
                                console.error('Error approving dental records:', xhr.responseText);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON.message || 'Failed to approve dental records.',
                                });
                            }
                        });
                    }
                });
            });

            // Bulk Reject Button Click Handler
            $('#bulk-reject-btn').on('click', function() {
                const selectedRecords = [];
                $('.select-record:checked').each(function() {
                    selectedRecords.push($(this).data('record-id'));
                });

                if (selectedRecords.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Selection',
                        text: 'Please select at least one dental record to reject.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Bulk Reject',
                    text: `Are you sure you want to reject ${selectedRecords.length} dental record(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, reject them!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showSpinner();

                        // Send AJAX request for bulk rejection
                        $.ajax({
                            url: '/admin/dental-records/bulk-reject', // Update with your route
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            data: {
                                record_ids: selectedRecords
                            },
                            success: function(data) {
                                hideSpinner();
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Rejected!',
                                        text: data.message || `${data.rejected_count} dental record(s) rejected successfully.`,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    // Reload DataTables to reflect changes
                                    table.ajax.reload();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: data.message || 'Failed to reject some dental records.',
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                hideSpinner();
                                console.error('Error rejecting dental records:', xhr.responseText);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON.message || 'Failed to reject dental records.',
                                });
                            }
                        });
                    }
                });
            });

            // Function to open image modal
            window.openImageModal = function(src) {
                const modal = document.getElementById('image-modal');
                const modalImg = document.getElementById('modal-image');
                modalImg.src = src;
                modal.style.display = 'flex';
            }

            // Function to close image modal
            window.closeImageModal = function() {
                const modal = document.getElementById('image-modal');
                modal.style.display = 'none';
            }

            // Function to approve a single dental record
            window.approveRecord = function(id) {
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
                        $.ajax({
                            url: `/admin/dental-record/${id}/approve`, // Update with your route
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            success: function(data) {
                                hideSpinner();
                                if (data.success) {
                                    Swal.fire(
                                        'Approved!',
                                        data.message,
                                        'success'
                                    ).then(() => {
                                        // Reload DataTables to reflect changes
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        data.message || 'The dental record could not be approved.',
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                hideSpinner();
                                console.error('Error:', error);
                                Swal.fire(
                                    'Error!',
                                    'There was a problem with the approval process.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            }

            // Function to reject a single dental record
            window.rejectRecord = function(id) {
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
                        $.ajax({
                            url: `/admin/dental-record/${id}/reject`, // Update with your route
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            success: function(data) {
                                hideSpinner();
                                if (data.success) {
                                    Swal.fire(
                                        'Rejected!',
                                        data.message,
                                        'success'
                                    ).then(() => {
                                        // Reload DataTables to reflect changes
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        data.message || 'The dental record could not be rejected.',
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                hideSpinner();
                                console.error('Error:', error);
                                Swal.fire(
                                    'Error!',
                                    'There was a problem with the rejection process.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            }

            // Helper Function to Escape HTML (Prevent XSS)
            window.escapeHtml = function(text) {
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

            // Close modal when clicking outside of the modal content
            window.onclick = function(event) {
                const imageModal = document.getElementById('image-modal');
                if (event.target == imageModal) {
                    imageModal.style.display = 'none';
                }
            }
        });
    </script>
    </x-app-layout>
