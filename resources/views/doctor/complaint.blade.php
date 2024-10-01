<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .main-content {
            margin-top: 60px;
        }

        .container {
            display: flex;
            font-family: 'Poppins', sans-serif;
            justify-content: space-between;
            min-height: 100vh;
        }

        /* Styling for the Form */
        .form-container {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 45%;
            box-sizing: border-box;
        }

        .form-group {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-group label {
            display: flex;
    align-items: center;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 5px;
        }
        .form-group label i {
    margin-right: 8px;
    color: #007bff;
    font-size: 18px;
}

        .form-group .input-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 50px;
            font-size: 14px;
            width: 100%;
            box-sizing: border-box;
        }

        .form-group textarea {
            border-radius: 20px;
            resize: none;
        }

        .form-group button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
            transition: background-color 0.3s;
        }

        .form-group button:hover {
            background-color: #00b8e6;
        }

        .search-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .search-input {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 25px;
    border: 1px solid #ddd;
    font-size: 16px;
    transition: all 0.3s ease;
}

        .search-container .input-wrapper {
            flex: 1;
        }

        .search-container button {
            background-color: #00d1ff;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 50px;
            margin-right: 250px;
            margin-bottom: 20px;
            cursor: pointer;
            font-size: 14px;
        }

        .search-container button:hover {
            background-color: #00b8e6;
        }

        .table-container {
    width: 100%;
    overflow-x: auto;
    max-height: 400px;
    overflow-y: auto;
    background-color: #f9f9f9;
    border-radius: 10px;
    border: 1px solid #ddd;
    padding: 15px;
}
.complaints-table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

        .complaints-table th,
        .complaints-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .complaints-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .complaints-table td {
            background-color: #fff;
        }

        .preview-button {
            background-color: #00d1ff;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .preview-button:hover {
            background-color: #00b8e6;
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
            justify-content: space-around;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 10px 10px 0 0;
        }

        .tab:hover {
            background-color: #c9d1d9;
        }

        .tab.active {
            background-color: #007bff;
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
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
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
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
        .input-container {
        position: relative;
        width: 100%;
        max-width: 400px;
        margin-bottom: 20px;
    }

    /* Styling for the input field */
    .input-container input {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #ccc;
    border-radius: 30px;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.input-container input:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}

/* Placeholder styling */
.input-container input::placeholder {
    color: #aaa;
    font-size: 14px;
}

    /* Styling for the icon */
    .input-container i {
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    color: #007bff;
    font-size: 18px;
}

.confine-status-wrapper {
    margin-top: 15px;
}

.radio-group {
    display: flex;
    gap: 20px;
    margin-top: 10px;
}

.radio-group label {
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}

.radio-group input[type="radio"] {
    margin-right: 5px;
}
    </style>

    <main class="main-content">
        <!-- Tabs for switching between Add Complaint and Complaint List -->
        <div class="tabs">
            <div class="tab active" onclick="showTab('add-complaint')">
                <i class="fas fa-plus-circle"></i> Add Complaint
            </div>
            <div class="tab" onclick="showTab('complaint-table')">
                <i class="fas fa-list-alt"></i> Complaint List
            </div>
        </div>

        <!-- Add Complaint Form Tab Content -->
        <div id="add-complaint" class="tab-content active">
            <div class="container">
                <div class="form-container">
                    <h2>Add Complaint</h2>

                    <!-- Search section -->
                    <div class="search-container">
                    <div class="form-group">
    <label for="id_number">
        <i class="fas fa-id-card"></i> ID Number
    </label>
    <div class="input-container">
    <input type="text" id="id_number" name="id_number" placeholder="Enter ID Number" maxlength="7">
    </div>
</div>

                        <button type="button" onclick="fetchPersonData()">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>

                    <form id="complaint-form" action="{{ route('doctor.complaint.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="role" id="role" value="">
                        <input type="hidden" name="year" value="{{ date('Y') }}">
                        <input type="hidden" name="id_number" id="hidden_id_number" value="">

                        <!-- First Name and Last Name fields -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <label for="first_name"><i class="fas fa-user"></i> First Name</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            </div>
                            <div class="input-wrapper">
                                <label for="last_name"><i class="fas fa-user"></i> Last Name</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            </div>
                        </div>

                        <!-- Age and Birthdate fields -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <label for="age"><i class="fas fa-hourglass-half"></i> Age</label>
                                <input type="number" id="age" name="age" required>
                            </div>
                            <div class="input-wrapper">
                                <label for="birthdate"><i class="fas fa-calendar"></i> Birthdate</label>
                                <input type="date" id="birthdate" name="birthdate" required>
                            </div>
                        </div>

                        <!-- Contact Number and Pain Assessment fields -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <label for="personal_contact_number"><i class="fas fa-phone"></i> Personal Contact Number</label>
                                <input type="text" id="personal_contact_number" name="personal_contact_number" value="">
                            </div>
                            <div class="input-wrapper">
                                <label for="pain_assessment"><i class="fas fa-thermometer-half"></i> Pain Assessment (1 to 10)</label>
                                <select id="pain_assessment" name="pain_assessment" required>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Medicine Given field -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <label for="medicine_given"><i class="fas fa-pills"></i> Medicine Given</label>
                                <select id="medicine_given" name="medicine_given" required></select>
                            </div>
                        </div>

                        <!-- Description of Sickness field -->
                        <div class="form-group">
    <div class="textarea-wrapper">
        <label for="sickness_description"><i class="fas fa-notes-medical"></i> Description of Sickness</label>
        <textarea id="sickness_description" name="sickness_description" rows="4" required></textarea>
    </div>

    <!-- Confine Status -->
    <div class="confine-status-wrapper">
        <label for="confine_status"><i class="fas fa-bed"></i> Confine Status</label>
        <div class="radio-group">
            <label>
                <input type="radio" name="confine_status" value="confined" required> Confined
            </label>
            <label>
                <input type="radio" name="confine_status" value="not_confined" required> Not Confined
            </label>
        </div>
    </div>
</div>

                        <!-- Submit button -->
                        <div class="form-group">
                            <button type="submit">
                                <i class="fas fa-paper-plane"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
                <div class="form-container">
                    <div class="table-container">
                        <div class="statistics-container">
                            <h3>Statistics</h3>
                            <div class="statistics-item">
                                <span>Most Common Complaint: </span>{{ $mostCommonComplaint }}
                            </div>
                            <div class="statistics-item">
                                <span>Most Used Medicine: </span>{{ $mostUsedMedicine }}
                            </div>
                            <div class="chart-container">
                                <canvas id="complaint-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <main class="main-content">
    <!-- Tabs for switching between Add Complaint and Complaint List -->
    <div class="tabs">
        <div class="tab active" onclick="showInnerTab('student-complaints')">Student Complaints</div>
        <div class="tab" onclick="showInnerTab('staff-complaints')">Staff Complaints</div>
        <div class="tab" onclick="showInnerTab('parent-complaints')">Parent Complaints</div>
        <div class="tab" onclick="showInnerTab('teacher-complaints')">Teacher Complaints</div>
    </div>

    <!-- Complaint List Tab Content -->
    <div id="complaint-table" class="tab-content">
        <!-- Student Complaints Tab -->
        <div id="student-complaints" class="tab-content active">
            <h2>Student Complaints</h2>
            <!-- Search bar for student complaints -->
            <input type="text" id="student-search" class="search-input" onkeyup="searchTable('student-search', 'student-complaints-table')" placeholder="Search for complaints..">
            <div class="table-container">
                <table class="complaints-table" id="student-complaints-table">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Description of Sickness</th>
                            <th>Pain Assessment</th>
                            <th>Medicine Given</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studentComplaints as $complaint)
                            <tr>
                                <td>{{ $complaint->first_name }}</td>
                                <td>{{ $complaint->last_name }}</td>
                                <td>{{ $complaint->sickness_description }}</td>
                                <td>{{ $complaint->pain_assessment }}</td>
                                <td>{{ $complaint->medicine_given }}</td>
                                <td><button class="preview-button" onclick="openModal({{ $complaint->id }})">Preview</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Staff Complaints Tab -->
        <div id="staff-complaints" class="tab-content">
            <h2>Staff Complaints</h2>
            <input type="text" id="staff-search" class="search-input" onkeyup="searchTable('staff-search', 'staff-complaints-table')" placeholder="Search for complaints..">
            <div class="table-container">
                <table class="complaints-table" id="staff-complaints-table">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Description of Sickness</th>
                            <th>Pain Assessment</th>
                            <th>Medicine Given</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($staffComplaints as $complaint)
                            <tr>
                                <td>{{ $complaint->first_name }}</td>
                                <td>{{ $complaint->last_name }}</td>
                                <td>{{ $complaint->sickness_description }}</td>
                                <td>{{ $complaint->pain_assessment }}</td>
                                <td>{{ $complaint->medicine_given }}</td>
                                <td><button class="preview-button" onclick="openModal({{ $complaint->id }})">Preview</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Parent Complaints Tab -->
        <div id="parent-complaints" class="tab-content">
            <h2>Parent Complaints</h2>
            <input type="text" id="parent-search" class="search-input" onkeyup="searchTable('parent-search', 'parent-complaints-table')" placeholder="Search for complaints..">
            <div class="table-container">
                <table class="complaints-table" id="parent-complaints-table">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Description of Sickness</th>
                            <th>Pain Assessment</th>
                            <th>Medicine Given</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($parentComplaints as $complaint)
                            <tr>
                                <td>{{ $complaint->first_name }}</td>
                                <td>{{ $complaint->last_name }}</td>
                                <td>{{ $complaint->sickness_description }}</td>
                                <td>{{ $complaint->pain_assessment }}</td>
                                <td>{{ $complaint->medicine_given }}</td>
                                <td><button class="preview-button" onclick="openModal({{ $complaint->id }})">Preview</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Teacher Complaints Tab -->
        <div id="teacher-complaints" class="tab-content">
            <h2>Teacher Complaints</h2>
            <input type="text" id="teacher-search" class="search-input" onkeyup="searchTable('teacher-search', 'teacher-complaints-table')" placeholder="Search for complaints..">
            <div class="table-container">
                <table class="complaints-table" id="teacher-complaints-table">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Description of Sickness</th>
                            <th>Pain Assessment</th>
                            <th>Medicine Given</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teacherComplaints as $complaint)
                            <tr>
                                <td>{{ $complaint->first_name }}</td>
                                <td>{{ $complaint->last_name }}</td>
                                <td>{{ $complaint->sickness_description }}</td>
                                <td>{{ $complaint->pain_assessment }}</td>
                                <td>{{ $complaint->medicine_given }}</td>
                                <td><button class="preview-button" onclick="openModal({{ $complaint->id }})">Preview</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

    <!-- Modal for Complaint Preview -->
    <div id="complaint-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="modal-body"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchAvailableMedicines();
            renderChart();
        });

        // Function to switch between main tabs
        function showTab(tabId) {
            console.log(`Switching to tab: ${tabId}`);

            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(tabContent => {
                tabContent.classList.remove('active');
            });

            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Show the selected tab content
            const selectedTabContent = document.getElementById(tabId);
            if (selectedTabContent) {
                selectedTabContent.classList.add('active');
            } else {
                console.error(`Tab content with id ${tabId} not found.`);
            }

            // Add active class to the selected tab
            document.querySelector(`.tab[onclick="showTab('${tabId}')"]`).classList.add('active');
        }

        // Function to switch between inner complaint tabs
        function showInnerTab(tabId) {
            // Hide all inner tab contents
            const innerTabContents = document.querySelectorAll('#complaint-table .tab-content');
            innerTabContents.forEach(innerTabContent => {
                innerTabContent.classList.remove('active');
            });

            // Remove active class from all inner tabs
            const innerTabs = document.querySelectorAll('#complaint-table .tab');
            innerTabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Show the selected inner tab content
            const selectedInnerTabContent = document.getElementById(tabId);
            if (selectedInnerTabContent) {
                selectedInnerTabContent.classList.add('active');
            } else {
                console.error(`Inner tab content with id ${tabId} not found.`);
            }

            // Add active class to the selected inner tab
            document.querySelector(`#complaint-table .tab[onclick="showInnerTab('${tabId}')"]`).classList.add('active');
        }

        // Fetch available medicines
        function fetchAvailableMedicines() {
            fetch('{{ route('doctor.inventory.available-medicines') }}')
                .then(response => response.json())
                .then(data => {
                    const medicineSelect = document.getElementById('medicine_given');
                    medicineSelect.innerHTML = '';
                    data.forEach(medicine => {
                        const option = document.createElement('option');
                        option.value = medicine;
                        option.textContent = medicine;
                        medicineSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching medicines:', error);
                });
        }

        // Fetch person data
        function fetchPersonData() {
            const idNumber = document.getElementById('id_number').value;

            if (!idNumber) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please enter an ID number.'
                });
                return;
            }

            fetch(`/doctor/complaint/person/${idNumber}`)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.error || 'Unknown error');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('first_name').value = data.first_name || '';
                    document.getElementById('last_name').value = data.last_name || '';
                    document.getElementById('age').value = data.age || '';
                    document.getElementById('birthdate').value = data.birthdate || '';
                    document.getElementById('personal_contact_number').value = data.personal_contact_number || '';
                    document.getElementById('role').value = data.role || '';
                    document.getElementById('hidden_id_number').value = data.id_number || '';

                    Swal.fire({
                        icon: 'success',
                        title: 'Person Data Fetched',
                        text: 'Person data successfully fetched.'
                    });
                })
                .catch(error => {
                    console.error('Error fetching person data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred: ' + error.message
                    });
                });
        }

        // Open modal to preview complaint details
        function openModal(complaintId) {
            fetch(`/doctor/complaint/${complaintId}`)
                .then(response => response.json())
                .then(data => {
                    const modalBody = document.getElementById('modal-body');
                    modalBody.innerHTML = `
                        <p><strong>First Name:</strong> ${data.first_name}</p>
                        <p><strong>Last Name:</strong> ${data.last_name}</p>
                        <p><strong>Pain Assessment:</strong> ${data.pain_assessment}</p>
                        <p><strong>Description of Sickness:</strong> ${data.sickness_description}</p>
                        <p><strong>Medicine Given:</strong> ${data.medicine_given}</p>
                    `;
                    document.getElementById('complaint-modal').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error fetching complaint details:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message
                    });
                });
        }

        // Close modal
        function closeModal() {
            document.getElementById('complaint-modal').style.display = 'none';
        }

        // Render chart for complaint statistics
        function renderChart() {
            const ctx = document.getElementById('complaint-chart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Most Common Complaint', 'Most Used Medicine'],
                    datasets: [{
                        label: 'Occurrences',
                        data: [{{ $commonComplaintCount }}, {{ $mostUsedMedicineCount }}],
                        backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                        borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        document.getElementById('complaint-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const form = event.target;
    const formData = new FormData(form); // Collect form data

    fetch("{{ route('doctor.complaint.store') }}", { // Use the admin route here
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // SweetAlert success message
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Complaint submitted successfully.'
            });

            // Clear all form fields
            form.reset();
        } else {
            // SweetAlert error message
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'An error occurred while submitting the form.'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);

        // SweetAlert error message for any network issues
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong. Please try again later.'
        });
    });
});
function searchTable(inputId, tableId) {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById(inputId);
    filter = input.value.toUpperCase();
    table = document.getElementById(tableId);
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {
        tr[i].style.display = "none";
        td = tr[i].getElementsByTagName("td");
        for (var j = 0; j < td.length; j++) {
            if (td[j]) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    break;
                }
            }
        }
    }
}

    </script>
</x-app-layout>