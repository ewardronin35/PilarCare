<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .container {
            display: flex;
        }

        .main-content {
            flex-grow: 1;
            margin-top: 30px;
            padding: 20px;
            margin-left: 80px;
            transition: margin-left 0.3s ease-in-out;
        }

        .sidebar:hover + .main-content {
            margin-left: 250px;
        }

        .tabs {
            display: flex;
            justify-content: center;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            background-color: #f8f9fa;
            color: #333;
        }

        .tab:hover {
            background-color: #e0e0e0;
        }

        .tab.active {
            border-bottom: 2px solid #0056b3;
            font-weight: bold;
            color: #0056b3;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .appointment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .appointment-table th,
        .appointment-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .appointment-table th {
            background-color: #00d1ff;
            color: white;
        }

        .form-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 1rem;
            flex: 1;
        }
        .dotor

        .form-group input,
        .form-group select {
            width: 48%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1rem;
        }

        .form-group button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .form-group button:active {
            transform: scale(0.95);
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

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1rem;
        }

        .action-buttons button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .action-buttons button:active {
            transform: scale(0.95);
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
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
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

        .profile-box {
            display: flex;
            align-items: center;
            padding: 20px;
            background-image: url('{{ asset('images/bg.jpg') }}');
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            color: white;
            width: calc(50% - 10px);
            animation: fadeInUp 0.5s ease-in-out;

        }

        .profile-box img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            margin-right: 20px;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
        }

        .profile-info h2 {
            margin: 0;
            font-size: 1.5em;
        }

        .profile-info p {
            margin: 0;
        }
    </style>

    <div class="container">
        <x-adminsidebar />

        <main class="main-content">
            <div class="tabs">
                <div class="tab active" onclick="showTab('add-appointment')">Add Appointment</div>
                <div class="tab" onclick="showTab('appointment-table')">Appointment List</div>
                <div class="tab" onclick="showTab('doctors')">Doctors</div>
            </div>

            <!-- Add Appointment Form -->
            <div id="add-appointment" class="tab-content active">
                <div class="form-container">
                    <h2>Add Appointment</h2>
                    <form id="add-form">
                        @csrf
                        <div class="form-group">
                            <label for="id-number">ID Number</label>
                            <input type="text" id="id-number" name="id_number" required oninput="fetchPatientName()">
                        </div>
                        <div class="form-group">
                            <label for="patient-name">Patient Name</label>
                            <input type="text" id="patient-name" name="patient_name" required>
                        </div>
                        <div class="form-group">
                            <label for="appointment-date">Appointment Date</label>
                            <input type="date" id="appointment-date" name="appointment_date" required>
                        </div>
                        <div class="form-group">
                            <label for="appointment-time">Appointment Time</label>
                            <input type="time" id="appointment-time" name="appointment_time" required>
                        </div>
                        <div class="form-group">
                            <label for="appointment-type">Appointment Type</label>
                            <select id="appointment-type" name="appointment_type" required>
                                <option value="Dr. Isnani">Dr. Isnani</option>
                                <option value="Dr. Nurmina">Dr. Nurmina</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="button" onclick="addAppointment()">Add Appointment</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Appointment Table Content -->
            <div id="appointment-table" class="tab-content">
                <table class="appointment-table">
                    <thead>
                        <tr>
                            <th>ID Number</th>
                            <th>Patient Name</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Appointment Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $appointment)
                            <tr id="appointment-row-{{ $appointment->id }}">
                                <td>{{ $appointment->id_number }}</td>
                                <td>{{ $appointment->patient_name }}</td>
                                <td>{{ $appointment->appointment_date }}</td>
                                <td>{{ $appointment->appointment_time }}</td>
                                <td>{{ $appointment->appointment_type }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button onclick="openEditModal({{ $appointment->id }})">Edit</button>
                                        <button onclick="confirmDelete({{ $appointment->id }})">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Doctors Profile -->
            <div id="doctors" class="tab-content">
                <div class="content-row">
                    <div class="profile-box">
                        <img src="https://img.icons8.com/?size=100&id=9570&format=png&color=000000" alt="Dr. Isnani">
                        <div class="profile-info">
                            <h2>Dr. Isnani</h2>
                            <p>General Physician</p>
                        </div>
                    </div>
                    <div class="profile-box">
                        <img src="https://img.icons8.com/?size=100&id=9570&format=png&color=000000" alt="Dr. Nurmina">
                        <div class="profile-info">
                            <h2>Dr. Nurmina</h2>
                            <p>School Dentist</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Appointment Modal -->
            <div id="edit-modal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeEditModal()">&times;</span>
                    <h2>Edit Appointment</h2>
                    <form id="edit-form">
                        @csrf
                        <input type="hidden" id="edit-appointment-id" name="id">
                        <div class="form-group">
                            <label for="edit-id-number">ID Number</label>
                            <input type="text" id="edit-id-number" name="id_number" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-patient-name">Patient Name</label>
                            <input type="text" id="edit-patient-name" name="patient_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-appointment-date">Appointment Date</label>
                            <input type="date" id="edit-appointment-date" name="appointment_date" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-appointment-time">Appointment Time</label>
                            <input type="time" id="edit-appointment-time" name="appointment_time" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-appointment-type">Appointment Type</label>
                            <select id="edit-appointment-type" name="appointment_type" required>
                                <option value="Dr. Isnani">Dr. Isnani</option>
                                <option value="Dr. Nurmina">Dr. Nurmina</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="button" onclick="updateAppointment()">Update Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
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

            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            document.querySelector(`.tab[onclick="showTab('${tabId}')"]`).classList.add('active');
        }

        function fetchPatientName() {
            // Fetch the patient name based on the ID number
            const idNumber = document.getElementById('id-number').value;
            // Replace this URL with the actual route to fetch patient details
            fetch(`/fetch-patient-name/${idNumber}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('patient-name').value = data.name;
                })
                .catch(error => console.error('Error fetching patient name:', error));
        }

        function closeNotification() {
            const notification = document.getElementById('notification');
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 300);
        }

        function showNotification(message) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
        }

        function openEditModal(id) {
            const appointment = document.getElementById(`appointment-row-${id}`);
            document.getElementById('edit-appointment-id').value = id;
            document.getElementById('edit-id-number').value = appointment.children[0].innerText;
            document.getElementById('edit-patient-name').value = appointment.children[1].innerText;
            document.getElementById('edit-appointment-date').value = appointment.children[2].innerText;
            document.getElementById('edit-appointment-time').value = appointment.children[3].innerText;
            document.getElementById('edit-appointment-type').value = appointment.children[4].innerText;
            document.getElementById('edit-modal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('edit-modal').style.display = 'none';
        }

        function addAppointment() {
            const form = document.getElementById('add-form');
            const formData = new FormData(form);
            fetch('{{ route("student.appointment.add") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.success);
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error adding appointment',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            }).catch(error => {
                console.error('Error:', error);
            });
        }

        function updateAppointment() {
            const form = document.getElementById('edit-form');
            const formData = new FormData(form);
            const id = document.getElementById('edit-appointment-id').value;
            const data = {};

            formData.forEach((value, key) => {
                data[key] = value;
            });

            fetch(`{{ url('/student/appointment/update/') }}/${id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.success);
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error updating appointment',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            }).catch(error => {
                console.error('Error:', error);
            });
        }

        function confirmDelete(id) {
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
                    fetch(`{{ url('/student/appointment/delete/') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`appointment-row-${id}`).remove();
                            showNotification('Appointment deleted successfully');
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error deleting appointment',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        }
    </script>
</x-app-layout>
