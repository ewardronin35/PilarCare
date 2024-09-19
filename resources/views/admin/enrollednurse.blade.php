<x-app-layout>
    <style>
        /* Add your existing styles here */
        .container {
            margin-top: 30px;
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }
        .main-content {
            margin-top: 10px;
        }
        .nurses-table {
            width: 95%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .nurses-table th, .nurses-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .nurses-table th {
            background-color: #00d2ff;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
        }

        .nurses-table td {
            background-color: #fff;
        }

        .preview-button {
            background-color: #00d1ff;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .preview-button:hover {
            background-color: #00b8e6;
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

        .switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 20px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 12px;
            width: 12px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #28a745;
        }

        input:checked + .slider:before {
            transform: translateX(14px);
        }

        .file-upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 2px dashed #00d1ff;
            padding: 20px;
            border-radius: 10px;
            background-color: #f9f9f9;
            margin-top: 20px;
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
            margin: 0 auto;
        }

        .file-upload-container input[type="file"] {
            display: none;
        }

        .file-upload-container label {
            display: block;
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            margin-bottom: 10px;
        }

        .file-upload-container label:hover {
            background-color: #00b8e6;
        }

        .file-upload-container .file-name {
            font-size: 16px;
            color: #333;
            margin-top: 10px;
        }

        .center-text {
            text-align: center;
        }

        .late-nurse-form-container {
            margin-top: 40px;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
        }

        .late-nurse-form-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .late-nurse-form-container label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .late-nurse-form-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .late-nurse-form-container button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .late-nurse-form-container button:hover {
            background-color: #00b8e6;
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: border-color 0.3s ease-in-out;
        }

        .tab.active {
            border-bottom: 2px solid #00d2ff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .edit-form {
            display: none;
            margin-top: 20px;
        }

        .delete-button {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        .save-button {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .save-button:hover {
            background-color: #218838;
        }

        .toggle-button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            margin-bottom: 10px;
        }

        .toggle-button:hover {
            background-color: #00b8e6;
        }

        #upload-section {
            transition: max-height 0.5s ease-in-out, opacity 0.5s ease-in-out;
            overflow: hidden;
            max-height: 500px;
            opacity: 1;
        }

        #upload-section.hidden {
            max-height: 0;
            opacity: 0;
            padding: 0;
            margin: 0;
        }

        .nurses-section {
            max-height: 400px;
            overflow-y: auto;
            margin-top: 20px;
        }

        .nurses-table-container {
            overflow-x: auto;
        }

        /* Search input styles */
        .search-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }

        .search-container input[type="text"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 300px;
        }

        /* Modal Background Overlay */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
            display: flex;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            width: 40%;
            max-width: 600px;
            animation: slideIn 0.5s ease-out;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover, .close:focus {
            color: #000;
            text-decoration: none;
        }

        /* Input Fields */
        .modal-content input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
            font-size: 14px;
        }

        .modal-content .save-button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
            display: inline-block;
            margin-top: 10px;
        }

        .modal-content .save-button:hover {
            background-color: #218838;
        }

        .modal-content h2 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #333;
            text-align: center;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>

    <div class="main-content">
        <div class="tabs">
            <div class="tab active" data-tab="upload-tab">Upload Nurse List</div>
            <div class="tab" data-tab="late-nurse-tab">Add Late Nurses</div>
            <div class="tab" data-tab="nurses-tab">View Nurses</div>
        </div>

        <div id="upload-tab" class="tab-content active">
            <div class="upload-section center-text">
                <h2>Upload Nurse List</h2>
                <p>Please ensure the Excel file follows the format: ID Number, First Name, Last Name, Department</p>
                <a href="{{ route('admin.download.nurse') }}" class="toggle-button">Download Excel Template</a>

                <button id="toggle-upload" class="toggle-button">Hide Upload Section</button>
                <div id="upload-section">
                    <form id="upload-form" enctype="multipart/form-data">
                        @csrf
                        <div class="file-upload-container">
                            <label for="file">Choose File</label>
                            <input type="file" name="file" id="file" required>
                            <div class="file-name" id="file-name">No file chosen</div>
                            <button type="submit" class="preview-button">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="late-nurse-tab" class="tab-content">
            <div class="late-nurse-form-container">
                <h2>Add Late Nurses</h2>
                <form id="late-nurse-form">
                    @csrf
                    <label for="late-id_number">ID Number</label>
                    <input type="text" id="late-id_number" name="late-id_number" required maxlength="7" pattern="[A-Za-z][0-9]{6}" title="ID number must start with a letter followed by 6 digits.">
                    <label for="late-first_name">First Name</label>
                    <input type="text" id="late-first_name" name="late-first_name" required>
                    <label for="late-last_name">Last Name</label>
                    <input type="text" id="late-last_name" name="late-last_name" required>
                    <label for="late-department">Department</label>
                    <input type="text" id="late-department" name="late-department" required>
                    <button type="submit" class="preview-button">Add Nurse</button>
                </form>
            </div>
        </div>

        <div id="nurses-tab" class="tab-content">
            <div class="nurses-section">
                <h2>Enrolled Nurses</h2>
                <div class="search-container">
                    <input type="text" id="nurse-search" placeholder="Search by ID, Name, or Department">
                </div>
                @if($nurses->isEmpty())
                    <p>No nurses enrolled yet.</p>
                @else
                    <table class="nurses-table" id="nurses-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="nurse-table-body">
                            @foreach($nurses as $nurse)
                                <tr id="nurse-row-{{ $nurse->id }}">
                                    <td>{{ $nurse->id_number }}</td>
                                    <td>{{ $nurse->first_name }}</td>
                                    <td>{{ $nurse->last_name }}</td>
                                    <td>{{ $nurse->department }}</td>
                                    <td>
                                        <button class="preview-button status-button" style="background-color: {{ $nurse->approved ? '#28a745' : '#dc3545' }};">
                                            {{ $nurse->approved ? 'Active' : 'Inactive' }}
                                        </button>
                                    </td>
                                    <td>
                                        <button class="preview-button edit-button" data-nurse-id="{{ $nurse->id }}">Edit</button>
                                        <button class="delete-button" onclick="deleteNurse({{ $nurse->id }})">Delete</button>
                                        <label class="switch">
                                            <input type="checkbox" class="toggle-approval" data-nurse-id="{{ $nurse->id }}" {{ $nurse->approved ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <!-- Edit Nurse Modal -->
        <div id="edit-nurse-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Edit Nurse</h2>
                <form id="edit-nurse-form">
                    @csrf
                    <input type="hidden" name="id" id="edit-nurse-id">
                    <label for="edit-id-number">ID Number</label>
                    <input type="text" name="id_number" id="edit-id-number" required>
                    <label for="edit-first-name">First Name</label>
                    <input type="text" name="first_name" id="edit-first-name" required>
                    <label for="edit-last-name">Last Name</label>
                    <input type="text" name="last_name" id="edit-last-name" required>
                    <label for="edit-department">Department</label>
                    <input type="text" name="department" id="edit-department" required>
                    <button type="submit" class="save-button">Save</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function openEditModal(nurse) {
            document.getElementById('edit-nurse-id').value = nurse.id;
            document.getElementById('edit-id-number').value = nurse.id_number;
            document.getElementById('edit-first-name').value = nurse.first_name;
            document.getElementById('edit-last-name').value = nurse.last_name;
            document.getElementById('edit-department').value = nurse.department;
            document.getElementById('edit-nurse-modal').style.display = 'flex';
        }

        document.querySelector('.close').addEventListener('click', function() {
            document.getElementById('edit-nurse-modal').style.display = 'none';
        });

        function deleteNurse(nurseId) {
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
                    fetch(`/admin/nurses/${nurseId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ _method: 'DELETE' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Deleted!', data.message, 'success');
                            document.getElementById('nurse-row-' + nurseId).remove();
                        } else {
                            Swal.fire('Error!', 'There was a problem deleting the nurse.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'There was a problem deleting the nurse.', 'error');
                    });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('edit-nurse-modal').style.display = 'none';

            document.getElementById('file').addEventListener('change', function(event) {
                const fileName = event.target.files[0].name;
                document.getElementById('file-name').textContent = fileName;
            });
            const nurseSearchInput = document.getElementById('nurse-search');
nurseSearchInput.addEventListener('input', function() {
    const searchValue = this.value.toLowerCase();
    const nurseRows = document.querySelectorAll('#nurses-table tbody tr');

    nurseRows.forEach(row => {
        const id = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
        const firstName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const lastName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const department = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

        // Show the row if any of the fields match the search value
        if (id.includes(searchValue) || firstName.includes(searchValue) || lastName.includes(searchValue) || department.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

            document.getElementById('upload-form').addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                fetch('{{ route('admin.nurses.import') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        fetchAndUpdateNurseTable(); 
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: data.errors.join('<br>'),
                            showConfirmButton: true,
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was a problem uploading the file.',
                        showConfirmButton: true,
                    });
                });
            });

            document.getElementById('late-nurse-form').addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                fetch('{{ route('admin.nurses.add') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        fetchAndUpdateNurseTable();
                        document.getElementById('late-nurse-form').reset();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: data.errors.join('<br>'),
                            showConfirmButton: true,
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was a problem adding the nurse.',
                        showConfirmButton: true,
                    });
                });
            });

            function fetchAndUpdateNurseTable() {
                fetch('{{ route('admin.nurses.enrolled') }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(nurses => {
                    updateNurseTable(nurses);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }

            function updateNurseTable(nurses) {
                var tbody = document.getElementById('nurse-table-body');
                if (!tbody) {
                    console.error("Element with ID 'nurse-table-body' not found.");
                    return;
                }
                tbody.innerHTML = '';
                nurses.forEach(nurse => {
                    var row = document.createElement('tr');
                    row.id = 'nurse-row-' + nurse.id;

                    row.innerHTML = `
                        <td>${nurse.id_number}</td>
                        <td>${nurse.first_name}</td>
                        <td>${nurse.last_name}</td>
                        <td>${nurse.department}</td>
                        <td>
                            <button class="preview-button status-button" style="background-color: ${nurse.approved ? '#28a745' : '#dc3545'};">
                                ${nurse.approved ? 'Active' : 'Inactive'}
                            </button>
                        </td>
                        <td>
                            <button class="preview-button edit-button" data-nurse-id="${nurse.id}">Edit</button>
                            <button class="delete-button" onclick="deleteNurse(${nurse.id})">Delete</button>
                            <label class="switch">
                                <input type="checkbox" class="toggle-approval" data-nurse-id="${nurse.id}" ${nurse.approved ? 'checked' : ''}>
                                <span class="slider"></span>
                            </label>
                        </td>`;
                    tbody.appendChild(row);
                });
                attachToggleApprovalEvents();
                attachEditEvents();
            }

            function attachToggleApprovalEvents() {
                document.querySelectorAll('.toggle-approval').forEach(input => {
                    var newInput = input.cloneNode(true);
                    input.parentNode.replaceChild(newInput, input);
                });

                document.querySelectorAll('.toggle-approval').forEach(input => {
                    input.addEventListener('change', function() {
                        var nurseId = this.getAttribute('data-nurse-id');
                        var approved = this.checked ? 1 : 0;

                        var formData = new FormData();
                        formData.append('approved', approved);

                        var actionUrl = `/admin/nurses/${nurseId}/toggle-approval`;

                        fetch(actionUrl, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                updateNurseRow(nurseId, data.nurse);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'There was a problem updating the nurse status.',
                                    showConfirmButton: true,
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'There was a problem updating the nurse status.',
                                showConfirmButton: true,
                            });
                        });
                    });
                });
            }

            function updateNurseRow(nurseId, nurse) {
                var row = document.getElementById('nurse-row-' + nurseId);
                if (!row) {
                    console.error(`Row for nurseId ${nurseId} not found`);
                    return;
                }

                var button = row.querySelector('.status-button');
                var checkbox = row.querySelector(`input[data-nurse-id="${nurseId}"]`);

                if (nurse.approved == 1) {
                    button.textContent = 'Active';
                    button.style.backgroundColor = '#28a745';
                } else {
                    button.textContent = 'Inactive';
                    button.style.backgroundColor = '#dc3545';
                }

                checkbox.checked = nurse.approved == 1;
            }

            function attachEditEvents() {
                document.querySelectorAll('.edit-button').forEach(button => {
                    button.addEventListener('click', function() {
                        var nurseId = this.getAttribute('data-nurse-id');

                        fetch(`/admin/nurses/${nurseId}`)
                        .then(response => response.json())
                        .then(nurse => {
                            openEditModal(nurse);
                        })
                        .catch(error => {
                            console.error('Error fetching nurse data:', error);
                            Swal.fire('Error', 'Unable to fetch nurse data', 'error');
                        });
                    });
                });
            }

            document.getElementById('edit-nurse-form').addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                var nurseId = document.getElementById('edit-nurse-id').value;

                fetch(`/admin/nurses/${nurseId}/edit`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        fetchAndUpdateNurseTable();
                        document.getElementById('edit-nurse-modal').style.display = 'none';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: data.errors.join('<br>'),
                            showConfirmButton: true,
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was a problem updating the nurse.',
                        showConfirmButton: true,
                    });
                });
            });

            // Tab switching functionality
            document.querySelectorAll('.tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));

                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                    this.classList.add('active');
                });
            });

            fetch('{{ route('admin.nurses.enrolled') }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(nurses => {
                updateNurseTable(nurses);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</x-app-layout>
