<x-app-layout :pageTitle="'Medical Record Approval'">   
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* General Styles */
        body {
            background-color: #f5f7fa;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .main-content {
            margin-top: 20px;
        
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

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        .badge-info {
            background-color: #17a2b8;
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

        /* Search Bar Styles */
        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-bar input {
            width: 100%;
            max-width: 400px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            font-size: 1rem;
            transition: box-shadow 0.3s;
        }

        .search-bar input:focus {
            box-shadow: 0 0 5px rgba(81, 203, 238, 1);
            border-color: rgba(81, 203, 238, 1);
        }
    </style>

    <div class="container">
        <main class="main-content">
            <!-- Tab Buttons -->
         
            <!-- Tab Content -->
            <div id="pending-approvals" class="tab-content active">
                <h1>Pending Medical Record Approvals</h1>

              
                <div class="table-container">
                    @if(isset($pendingMedicalRecords) && $pendingMedicalRecords->isNotEmpty())
                        <table id="medicalRecordsTable">
                            <!-- Table Headers -->
                            <thead>
                                <tr>
                                    <th>Record Date</th>
                                    <th>Name</th>
                                    <th>Birthdate</th>
                                    <th>Age</th>
                                    <th>Address</th>
                                    <th>Contact Number</th>
                                    <th>Medicines</th>
                                    <th>Past Illness</th>
                                    <th>Chronic Conditions</th>
                                    <th>Surgical History</th>
                                    <th>Family Medical History</th>
                                    <th>Allergies</th>
                                    <th>Medical Condition</th>
                                    <th>Health Documents</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <!-- Table Rows -->
                            <tbody id="medicalRecordsTbody">
                                @foreach($pendingMedicalRecords as $medicalRecord)
                                    <tr data-id="{{ $medicalRecord->id }}">
                                        <td>{{ \Carbon\Carbon::parse($medicalRecord->record_date)->format('Y-m-d') }}</td>
                                        <td>{{ $medicalRecord->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($medicalRecord->birthdate)->format('Y-m-d') }}</td>
                                        <td>{{ $medicalRecord->age }}</td>
                                        <td>{{ $medicalRecord->address }}</td>
                                        <td>{{ $medicalRecord->personal_contact_number }}</td>
                                        
                                        <td>
                                            @if(is_array($medicalRecord->medicines) && count($medicalRecord->medicines) > 0)
                                                @foreach($medicalRecord->medicines as $medicine)
                                                    <span class="badge badge-primary">{{ $medicine }}</span>
                                                @endforeach
                                            @else
                                                <span class="badge badge-secondary">N/A</span>
                                            @endif
                                        </td>
                                        
                                        <!-- Additional Fields -->
                                        <td>{{ $medicalRecord->past_illness }}</td>
                                        <td>{{ $medicalRecord->chronic_conditions }}</td>
                                        <td>{{ $medicalRecord->surgical_history }}</td>
                                        <td>{{ $medicalRecord->family_medical_history }}</td>
                                        <td>{{ $medicalRecord->allergies }}</td>
                                        <td>{{ $medicalRecord->medical_condition }}</td>
                                        <td>
                                            @if(is_array($medicalRecord->health_documents) && count($medicalRecord->health_documents) > 0)
                                                @foreach($medicalRecord->health_documents as $doc)
                                                    <a href="{{ asset('storage/' . $doc) }}" target="_blank" class="badge badge-info">
                                                        <i class="fas fa-file"></i> View Document
                                                    </a>
                                                @endforeach
                                            @else
                                                <span class="badge badge-secondary">N/A</span>
                                            @endif
                                        </td>
                                        
                                        <!-- Action Buttons -->
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
                        {{ $pendingMedicalRecords->links() }}
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
            <span class="close" onclick="closePreviewModal()">&times;</span>
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

    <!-- Modal for Medical Record Details -->
    <div id="medicalRecordModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeMedicalRecordModal()">&times;</span>
            <div class="modal-header">
                <h2>Medical Record Details</h2>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- Details will be injected here via JavaScript -->
            </div>
        </div>
    </div>

    <!-- SweetAlert2 Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables Library -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const medicalRecordsTbody = document.getElementById('medicalRecordsTbody');
            initializeDataTable();

            // Initialize DataTable
            function initializeDataTable() {
                $('#medicalRecordsTable').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: true,
                    autoWidth: false
                });
            }

            // Search functionality (Optional)
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const table = $('#medicalRecordsTable').DataTable();
                    table.search(this.value).draw();
                });
            }
        });

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
        function openPreviewModal(src) {
            const modal = document.getElementById('previewModal');
            const modalImg = document.getElementById('modal-image');
            modalImg.src = src;
            modal.style.display = 'block';
        }

        // Function to close image modal
        function closePreviewModal() {
            const modal = document.getElementById('previewModal');
            modal.style.display = 'none';
        }

        // Function to open medical record modal
        function openMedicalRecordModal(medicalRecordId) {
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            fetch(`/admin/medical-record/${medicalRecordId}`)
                .then(response => response.json())
                .then(data => {
                    Swal.close(); // Close the loading spinner
                    if (data.success) {
                        const record = data.medicalRecord;
                        const medicines = record.medicines ? JSON.parse(record.medicines) : [];
                        const healthDocuments = record.health_documents ? JSON.parse(record.health_documents) : [];

                        let modalContent = `
                            <p><strong>Record Date:</strong> ${new Date(record.record_date).toLocaleDateString()}</p>
                            <p><strong>Name:</strong> ${record.name}</p>
                            <p><strong>Birthdate:</strong> ${new Date(record.birthdate).toLocaleDateString()}</p>
                            <p><strong>Age:</strong> ${record.age}</p>
                            <p><strong>Address:</strong> ${record.address}</p>
                            <p><strong>Contact Number:</strong> ${record.personal_contact_number}</p>
                            <p><strong>Past Illness:</strong> ${record.past_illness}</p>
                            <p><strong>Chronic Conditions:</strong> ${record.chronic_conditions}</p>
                            <p><strong>Surgical History:</strong> ${record.surgical_history}</p>
                            <p><strong>Family Medical History:</strong> ${record.family_medical_history}</p>
                            <p><strong>Allergies:</strong> ${record.allergies}</p>
                            <p><strong>Medical Condition:</strong> ${record.medical_condition}</p>
                            <p><strong>Medicines:</strong> ${medicines.join(', ')}</p>
                            <p><strong>Health Documents:</strong> 
                        `;

                        if (healthDocuments.length > 0) {
                            healthDocuments.forEach(doc => {
                                modalContent += `<a href="${window.location.origin}/storage/${doc}" target="_blank" class="badge badge-info"><i class="fas fa-file"></i> View Document</a> `;
                            });
                        } else {
                            modalContent += `<span class="badge badge-secondary">N/A</span>`;
                        }

                        modalContent += `</p>`;

                        if (record.profile_picture) {
                            modalContent += `
                                <p><strong>Profile Picture:</strong></p>
                                <img src="${window.location.origin}/storage/${record.profile_picture}" alt="Profile Picture" style="max-width: 100%; height: auto;">
                            `;
                        }

                        document.getElementById('modal-body').innerHTML = modalContent;
                        document.getElementById('medicalRecordModal').style.display = 'block';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to fetch medical record details.'
                        });
                    }
                })
                .catch(error => {
                    Swal.close(); // Close the loading spinner
                    console.error('Error fetching medical record details:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred.'
                    });
                });
        }

        // Function to close medical record modal
        function closeMedicalRecordModal() {
            const modal = document.getElementById('medicalRecordModal');
            modal.style.display = 'none';
        }

        // Function to fetch new records (Polling)
        function fetchNewRecords() {
            fetch('/admin/medical-records/pending', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('AJAX Response:', data); // Debugging

                if (data.success && Array.isArray(data.records) && data.records.length > 0) {
                    data.records.forEach(record => {
                        console.log('Processing Record:', record); // Debugging

                        // Handle medicines safely
                        let medicinesHTML = '<span class="badge badge-secondary">N/A</span>';
                        if (Array.isArray(record.medicines) && record.medicines.length > 0) {
                            medicinesHTML = record.medicines.map(med => `<span class="badge badge-primary">${med}</span>`).join(' ');
                        }

                        // Handle health_documents safely
                        let healthDocumentsHTML = '<span class="badge badge-secondary">N/A</span>';
                        if (Array.isArray(record.health_documents) && record.health_documents.length > 0) {
                            healthDocumentsHTML = record.health_documents.map(doc => `<a href="${window.location.origin}/storage/${doc}" target="_blank" class="badge badge-info"><i class="fas fa-file"></i> View Document</a>`).join(' ');
                        }

                        // Check if the record already exists in the table
                        if (!document.querySelector(`tr[data-id='${record.id}']`)) {
                            // Create a new table row
                            const newRow = document.createElement('tr');
                            newRow.setAttribute('data-id', record.id);

                            newRow.innerHTML = `
                                <td>${new Date(record.record_date).toLocaleDateString()}</td>
                                <td>${record.name}</td>
                                <td>${new Date(record.birthdate).toLocaleDateString()}</td>
                                <td>${record.age}</td>
                                <td>${record.address}</td>
                                <td>${record.personal_contact_number}</td>
                                <td>
                                    ${medicinesHTML}
                                </td>
                                <td>${record.past_illness}</td>
                                <td>${record.chronic_conditions}</td>
                                <td>${record.surgical_history}</td>
                                <td>${record.family_medical_history}</td>
                                <td>${record.allergies}</td>
                                <td>${record.medical_condition}</td>
                                <td>
                                    ${healthDocumentsHTML}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success" onclick="approveRecord(${record.id})">
                                        <i class="fas fa-check-circle"></i> Approve
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="rejectRecord(${record.id})">
                                        <i class="fas fa-times-circle"></i> Reject
                                    </button>
                                </td>
                            `;

                            medicalRecordsTbody.appendChild(newRow);
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching new records:', error);
            });
        }

        // Initial fetch
        fetchNewRecords();

        // Set interval for polling (e.g., every 30 seconds)
        setInterval(fetchNewRecords, 30000); // 30000 milliseconds = 30 seconds

        // Function to approve medical record with spinner
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
                    // Show spinner
                    Swal.fire({
                        title: 'Approving...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    fetch(`/admin/medical-record/${id}/approve`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close(); // Close the spinner
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
                        Swal.close(); // Close the spinner
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

        // Function to reject medical record with spinner
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
                    // Show spinner
                    Swal.fire({
                        title: 'Rejecting...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

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
                        Swal.close(); // Close the spinner
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
                        Swal.close(); // Close the spinner
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

        // Function to close medical record modal
        function closeMedicalRecordModal() {
            const modal = document.getElementById('medicalRecordModal');
            modal.style.display = 'none';
        }

        // Event listener to close modals when clicking outside the modal content
        window.onclick = function(event) {
            const previewModal = document.getElementById('previewModal');
            const noRecordModal = document.getElementById('noRecordModal');
            const medicalRecordModal = document.getElementById('medicalRecordModal');
            if (event.target == previewModal) {
                previewModal.style.display = 'none';
            }
            if (event.target == noRecordModal) {
                noRecordModal.style.display = 'none';
            }
            if (event.target == medicalRecordModal) {
                medicalRecordModal.style.display = 'none';
            }
        }
    </script>
</x-app-layout>
