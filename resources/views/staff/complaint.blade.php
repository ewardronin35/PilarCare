<x-app-layout :pageTitle="'Complaints'">
>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-yH6jIwETnIsIv+J8bGZ1CST5Q3gqLbG3Ehj3FdJ9VqgqkrFuzqfY1JIDxr2VDYfv4pZwU6yD/WHx3Q+5GfYbQg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- SweetAlert2 for Alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJ+Y5e3U6IY+PffyONVfQK3rWZ6+P1Rrz4jAE="
            crossorigin="anonymous"></script>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    
    <style>
        /* Existing Styles */

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            animation: fadeInBackground 1s ease-in-out;
            background-color: #f5f7fa;
        }

        .main-content {
            margin-top: 30px;
        }

        .alert {
            padding: 15px;
            background-color: #4CAF50; /* Green */
            color: white;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .complaints-section {
            margin-right: 50px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            animation: fadeIn 0.5s ease-in-out;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .complaints-section h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            animation: slideDown 0.5s ease-in-out;
        }

        /* Scrollable Table Container */
        .table-container {
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .complaints-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .complaints-table thead {
            position: sticky;
            top: 0;
            background-color: #f5f5f5;
            z-index: 1;
        }

        .complaints-table th,
        .complaints-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .complaints-table th {
            font-weight: 600;
            color: #333;
        }

        .complaints-table tbody tr:hover {
            background-color: #f9f9f9;
            cursor: pointer;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap; /* Allows buttons to wrap on smaller screens */
        }

        .preview-button,
        .pdf-button,
        .download-button {
            background-color: #00d1ff;
            color: white;
            padding: 5px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none; /* For anchor tags */
        }

        .preview-button:hover,
        .pdf-button:hover,
        .download-button:hover {
            background-color: #00b8e6;
        }

        .pdf-button[disabled],
        .pdf-button[disabled]:hover,
        .download-button[disabled],
        .download-button[disabled]:hover {
            background-color: #6c757d;
            cursor: not-allowed;
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
            overflow-y: auto;
            background-color: rgba(0, 0, 0, 0.4);
            animation: fadeIn 0.5s ease-in-out;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 600px;
            border-radius: 10px;
            animation: slideIn 0.5s ease-in-out;
            position: relative;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5em;
            color: #333;
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
        }

        .modal-body {
            margin-top: 10px;
        }

        .modal-body p {
            margin: 10px 0;
            color: #555;
        }

        .modal-body a.pdf-link {
            display: inline-block;
            margin-top: 15px;
            background-color: #28a745;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease-in-out;
        }

        .modal-body a.pdf-link:hover {
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

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .modal-content {
                width: 95%;
            }

            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }

            .preview-button,
            .pdf-button,
            .download-button {
                width: 100%;
                text-align: center;
            }

            .search-bar input[type="text"] {
                width: 80%;
                margin-bottom: 10px;
            }

            .search-bar button {
                width: 80%;
            }
        }

        /* Search Form Styles */
        .search-form {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-bar input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar button {
            padding: 10px 15px;
            border: none;
            background-color: #00d1ff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        .search-bar button:hover {
            background-color: #00b8e6;
        }

        /* Filter Buttons */
        .filter-buttons {
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-button {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            font-size: 1rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .filter-button.active,
        .filter-button:hover {
            background-color: #00d1ff;
        }

    </style>

    <div class="main-content">
        @if(session('success'))
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter Buttons -->
        <div class="filter-buttons">
            <a href="{{ route('staff.complaint', ['filter' => 'present']) }}" class="filter-button {{ request('filter') === 'present' ? 'active' : '' }}">
                <i class="fas fa-sun"></i> Present Complaint
            </a>
            <a href="{{ route('staff.complaint', ['filter' => 'past']) }}" class="filter-button {{ request('filter') === 'past' ? 'active' : '' }}">
                <i class="fas fa-moon"></i> Past Complaint
            </a>
        </div>

        <!-- Search Form -->
      
        <!-- Complaints Section -->
        <div class="complaints-section">
            <h2>Student Complaint History</h2>

            <!-- Complaints Table -->
            <div class="table-container">
                <table class="complaints-table" id="complaints-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description of Sickness</th>
                            <th>Pain Assessment</th>
                            <th>Confine Status</th>
                            <th>Medicine Given</th>
                            <th>Record Date</th> <!-- Added Record Date -->
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($complaints as $complaint)
                            <tr>
                                <td>{{ $complaint->first_name }} {{ $complaint->last_name }}</td>
                                <td>{{ $complaint->sickness_description }}</td>
                                <td>{{ $complaint->pain_assessment }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $complaint->confine_status)) }}</td>
                                <td>{{ $complaint->medicine_given }}</td>
                                <td>{{ $complaint->created_at->format('M d, Y') }}</td> <!-- Record Date Based on created_at -->
                                <td>
                                    <div class="action-buttons">
                                        <button class="preview-button" onclick="openPreviewModal({{ $complaint->id }})">
                                            <i class="fas fa-eye"></i> Preview
                                        </button>
                                        @if(isset($complaint->report_url))
                                            <a href="{{ $complaint->report_url }}" target="_blank" class="pdf-button">
                                                <i class="fas fa-file-pdf"></i> View PDF
                                            </a>
                                            <a href="{{ $complaint->report_url }}" download class="download-button">
                                                <i class="fas fa-download"></i> Download PDF
                                            </a>
                                        @else
                                            <button class="pdf-button" disabled title="PDF not available">
                                                <i class="fas fa-file-pdf"></i> View PDF
                                            </button>
                                            <button class="download-button" disabled title="PDF not available">
                                                <i class="fas fa-download"></i> Download PDF
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>

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

    <script>
        $(document).ready(function() {
            // Initialize DataTables for Complaints Table
            $('#complaints-table').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "emptyTable": "No complaints found."
                }
            });

            // Handle Submenu Toggles (if any)
            // Since this template doesn't have a sidebar, this part can be omitted or adjusted based on your actual layout
        });

        // Function to open modal and load complaint details
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
                        <p><strong>Name:</strong> ${data.first_name} ${data.last_name}</p>
                        <p><strong>Description of Sickness:</strong> ${data.sickness_description}</p>
                        <p><strong>Pain Assessment:</strong> ${data.pain_assessment}</p>
                        <p><strong>Confine Status:</strong> ${data.confine_status}</p>
                        <p><strong>Medicine Given:</strong> ${data.medicine_given}</p>
                        <p><strong>Record Date:</strong> ${data.created_at ? new Date(data.created_at).toLocaleDateString() : 'N/A'}</p>
                        ${data.pdf_url ? `
                            <a href="${data.pdf_url}" target="_blank" class="pdf-link">
                                <i class="fas fa-file-pdf"></i> View PDF
                            </a>
                        ` : ''}
                    `;
                    document.getElementById('preview-modal').style.display = 'flex';
                })
                .catch(error => {
                    console.error('Error fetching complaint details:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load complaint details.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                });
        }

        // Function to close modal
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close modal when clicking outside of the modal content
        window.onclick = function(event) {
            const previewModal = document.getElementById('preview-modal');
            if (event.target == previewModal) {
                previewModal.style.display = 'none';
            }
        }
    </script>
</x-app-layout>
