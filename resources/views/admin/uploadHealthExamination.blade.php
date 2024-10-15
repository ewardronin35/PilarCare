<!-- resources/views/admin-health-examinations.blade.php -->
<x-app-layout>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 for Alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            max-height: 600px; /* Adjust as needed */
            overflow-y: auto;
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

        .modal-body img {
            width: 100%;
            height: auto;
            border-radius: 5px;
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

            .search-bar input {
                width: 200px;
            }
        }

        /* School Year Reset Styles */
        .school-year-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px 0;
        }

        .school-year-container select {
            padding: 10px;
            font-size: 16px;
            margin-bottom: 10px;
            width: 200px;
        }

        .reset-button {
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        .reset-button:hover {
            background-color: #c82333;
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
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Search by patient name or examination ID..." />
            <button onclick="searchExaminations()">Search</button>
        </div>

        <!-- Tab Navigation -->
        <div class="tabs">
            <button class="tab-btn active" data-role="pending-approvals" onclick="switchTab('pending-approvals')">
                <i class="fas fa-file-medical"></i> Pending Approvals
            </button>
            <button class="tab-btn" data-role="school-year-reset" onclick="switchTab('school-year-reset')">
                <i class="fas fa-calendar-alt"></i> School Year Reset
            </button>
        </div>

        <!-- Pending Approvals Tab Content -->
        <div class="table-container tab-content" id="pending-approvals">
            <table id="health-examinations-table">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>School Year</th>
                        <th>Health Exam Pictures</th>
                        <th>X-ray Pictures</th>
                        <th>Lab Result Pictures</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingExaminations as $examination)
                        <tr data-id="{{ $examination->id }}">
                            <td>{{ $examination->user->first_name }} {{ $examination->user->last_name }}</td>
                            <td>{{ $examination->school_year }}</td>

                            <!-- Health Examination Pictures -->
                            <td>
                                <div class="image-previews">
                                    @forelse(($examination->health_examination_picture ?? []) as $healthPic)
                                        <img src="{{ asset('storage/' . $healthPic) }}" alt="Health Examination Picture" onclick="openImageModal('{{ asset('storage/' . $healthPic) }}')">
                                    @empty
                                        <p>No Health Examination pictures uploaded.</p>
                                    @endforelse
                                </div>
                            </td>

                            <!-- X-ray Pictures -->
                            <td>
                                <div class="image-previews">
                                    @forelse(($examination->xray_picture ?? []) as $xray)
                                        <img src="{{ asset('storage/' . $xray) }}" alt="X-ray Picture" onclick="openImageModal('{{ asset('storage/' . $xray) }}')">
                                    @empty
                                        <p>No X-ray pictures uploaded.</p>
                                    @endforelse
                                </div>
                            </td>

                            <!-- Lab Result Pictures -->
                            <td>
                                <div class="image-previews">
                                    @forelse(($examination->lab_result_picture ?? []) as $lab)
                                        <img src="{{ asset('storage/' . $lab) }}" alt="Lab Result Picture" onclick="openImageModal('{{ asset('storage/' . $lab) }}')">
                                    @empty
                                        <p>No Lab Result pictures uploaded.</p>
                                    @endforelse
                                </div>
                            </td>

                            <!-- Actions (Approve/Reject) -->
                            <td>
                                <button type="button" class="btn btn-success" onclick="approveExamination({{ $examination->id }})">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button type="button" class="btn btn-danger" onclick="rejectExamination({{ $examination->id }})">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #888;">No pending examinations available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- School Year Reset Tab Content -->
        <div class="school-year-container tab-content" id="school-year-reset" style="display: none;">
            <h2>Select School Year to Reset Data</h2>
            <select id="school-year-select">
                @foreach($schoolYears as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>

            <button type="button" class="reset-button" id="reset-school-year">Reset School Year</button>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div id="image-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Image Preview</h2>
                <span class="close" onclick="closeImageModal()">&times;</span>
            </div>
            <div class="modal-body">
                <img id="modal-image" src="" alt="Image Preview">
            </div>
        </div>
    </div>

    <!-- Spinner Overlay -->
    <div id="spinner-overlay">
        <div class="spinner"></div>
    </div>

    <script>
        // CSRF Token Setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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

        // Function to approve a health examination
        function approveExamination(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to approve this health examination!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showSpinner();
                    fetch(`/admin/health-examination/${id}/approve`, {
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
                                data.message || 'The examination has been approved.',
                                'success'
                            ).then(() => {
                                // Remove the approved row without reloading
                                const row = document.querySelector(`tr[data-id="${id}"]`);
                                if (row) {
                                    row.remove();
                                }
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'The examination could not be approved.',
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

        // Function to reject a health examination
        function rejectExamination(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to reject this health examination!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showSpinner();
                    fetch(`/admin/health-examination/${id}/reject`, {
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
                                data.message || 'The examination has been rejected.',
                                'success'
                            ).then(() => {
                                // Remove the rejected row without reloading
                                const row = document.querySelector(`tr[data-id="${id}"]`);
                                if (row) {
                                    row.remove();
                                }
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'The examination could not be rejected.',
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
        function switchTab(tabId) {
            showSpinner(); // Show spinner when switching tabs

            // Remove 'active' class from all tab buttons
            const tabButtons = document.querySelectorAll('.tab-btn');
            tabButtons.forEach(button => button.classList.remove('active'));

            // Add 'active' class to the clicked tab
            const activeTab = document.querySelector(`.tab-btn[data-role="${tabId}"]`);
            if (activeTab) {
                activeTab.classList.add('active');
            }

            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.style.display = 'none');

            // Show the selected tab content
            const activeContent = document.getElementById(tabId);
            if (activeContent) {
                // Simulate data loading with a timeout (if data loading is not needed)
                setTimeout(() => {
                    activeContent.style.display = (tabId === 'school-year-reset') ? 'flex' : 'block';
                    hideSpinner(); // Hide spinner after content is displayed
                }, 500); // Adjust timeout as needed
            } else {
                hideSpinner(); // Hide spinner if content not found
            }

            // Optionally, refresh data based on the active tab
            if (tabId === 'pending-approvals') {
                fetchPendingExaminations(); // Fetch pending approvals
            }
        }

        // Function to fetch pending examinations based on search query
        function fetchPendingExaminations(searchQuery = '', showSpinnerOption = true) {
            const tableBody = document.querySelector('#health-examinations-table tbody');
            tableBody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: #555;">Loading...</td></tr>';

            if (showSpinnerOption) {
                showSpinner();
            }

            fetch(`/admin/health-examinations/pending-data?search=${encodeURIComponent(searchQuery)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                if (showSpinnerOption) {
                    hideSpinner();
                }
                if (!response.ok) {
                    throw new Error(`Server responded with status ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                tableBody.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(examination => {
                        // Generate HTML for Health Examination Pictures
                        let healthPicsHtml = '';
                        if (examination.health_examination_pictures.length > 0) {
                            healthPicsHtml = `<div class="image-previews">`;
                            examination.health_examination_pictures.forEach(picUrl => {
                                healthPicsHtml += `<img src="${picUrl}" alt="Health Examination Picture" onclick="openImageModal('${picUrl}')"/>`;
                            });
                            healthPicsHtml += `</div>`;
                        } else {
                            healthPicsHtml = `<p>No Health Examination pictures uploaded.</p>`;
                        }

                        // Generate HTML for X-ray Pictures
                        let xrayPicsHtml = '';
                        if (examination.xray_pictures.length > 0) {
                            xrayPicsHtml = `<div class="image-previews">`;
                            examination.xray_pictures.forEach(xrayUrl => {
                                xrayPicsHtml += `<img src="${xrayUrl}" alt="X-ray Picture" onclick="openImageModal('${xrayUrl}')"/>`;
                            });
                            xrayPicsHtml += `</div>`;
                        } else {
                            xrayPicsHtml = `<p>No X-ray pictures uploaded.</p>`;
                        }

                        // Generate HTML for Lab Result Pictures
                        let labPicsHtml = '';
                        if (examination.lab_result_pictures.length > 0) {
                            labPicsHtml = `<div class="image-previews">`;
                            examination.lab_result_pictures.forEach(labUrl => {
                                labPicsHtml += `<img src="${labUrl}" alt="Lab Result Picture" onclick="openImageModal('${labUrl}')"/>`;
                            });
                            labPicsHtml += `</div>`;
                        } else {
                            labPicsHtml = `<p>No Lab Result pictures uploaded.</p>`;
                        }

                        // Append the row to the table
                        tableBody.innerHTML += `
                            <tr data-id="${examination.id}">
                                <td>${escapeHtml(examination.user_name)}</td>
                                <td>${escapeHtml(examination.school_year)}</td>
                                <td>
                                    ${healthPicsHtml}
                                </td>
                                <td>
                                    ${xrayPicsHtml}
                                </td>
                                <td>
                                    ${labPicsHtml}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success" onclick="approveExamination(${examination.id})">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="rejectExamination(${examination.id})">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" style="text-align: center; color: #888;">No pending examinations found.</td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                if (showSpinnerOption) {
                    hideSpinner();
                }
                console.error('Error fetching pending examinations:', error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align: center; color: #dc3545;">Failed to load pending examinations. Please try again later.</td>
                    </tr>
                `;
            });
        }

        // Function to handle search
        function searchExaminations() {
            const searchQuery = document.getElementById('search-input').value.trim();
            fetchPendingExaminations(searchQuery); // Spinner will show by default
        }

        // Helper Function to Escape HTML (Prevent XSS)
        function escapeHtml(text) {
            if (!text) return '';
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

        // Real-Time Refresh Function
        function startRealTimeRefresh() {
            // Fetch and update the table every 10 seconds (10000 milliseconds)
            setInterval(() => {
                const activeTab = document.querySelector('.tab-btn.active');
                const role = activeTab ? activeTab.getAttribute('data-role') : 'pending-approvals';
                const searchQuery = document.getElementById('search-input').value.trim();
                if (role === 'pending-approvals') {
                    fetchPendingExaminations(searchQuery, false); // Do not show spinner during real-time refresh
                }
                // Add additional conditions if other tabs require periodic refresh
            }, 10000);
        }

        // Initialize with default tab and fetch records
        document.addEventListener('DOMContentLoaded', function () {
            const defaultRole = 'pending-approvals';
            switchTab(defaultRole); // Switch to the default tab
            fetchPendingExaminations(); // Spinner will show by default
            startRealTimeRefresh(); // Start real-time refresh
        });

        // Close modal when clicking outside of the modal content
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        }

        // Function to reset school year
        document.getElementById('reset-school-year').addEventListener('click', function () {
            const selectedYear = document.getElementById('school-year-select').value;

            // Confirmation Dialog before Reset
            Swal.fire({
                title: 'Are you sure?',
                text: `You want to reset the school year to ${selectedYear}? Users will need to upload new health examinations for this year.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reset it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showSpinner();

                    // Send AJAX request to reset school year data
                    fetch(`/admin/reset-school-year`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            school_year: selectedYear
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideSpinner();
                        if (data.success) {
                            Swal.fire(
                                'Reset!',
                                data.message || `The school year has been reset to ${selectedYear}. Users can now upload new health examinations.`,
                                'success'
                            ).then(() => {
                                // Optionally, perform additional actions like reloading the page or updating UI
                                location.reload(); // Reload to reflect changes
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Failed to reset the school year data.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        hideSpinner();
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'There was a problem resetting the school year data.',
                            'error'
                        );
                    });
                }
            });
        });
    </script>

</x-app-layout>
