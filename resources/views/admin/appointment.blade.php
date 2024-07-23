<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .container {
            display: flex;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 80px;
            transition: margin-left 0.3s ease-in-out;
        }

        .sidebar:hover + .main-content {
            margin-left: 250px;
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .tab:hover {
            background-color: #f0f0f0;
        }

        .tab.active {
            border-bottom: 2px solid #007bff;
            font-weight: bold;
            color: #007bff;
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
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 1rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
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
    </style>

    <div class="container">
        <x-adminsidebar />

        <main class="main-content">
            <h1>Appointments</h1>

            <!-- Tabs -->
            <div class="tabs">
                <div class="tab active" onclick="showTab('add-appointment')">Add Appointment</div>
                <div class="tab" onclick="showTab('appointment-table')">Appointment List</div>
            </div>

            <!-- Add Appointment Form -->
            <div id="add-appointment" class="tab-content active">
                <div class="form-container">
                    <h2>Add Appointment</h2>
                    <form id="add-form">
                        @csrf
                        <div class="form-group">
                            <label for="id-number">ID Number</label>
                            <input type="text" id="id-number" name="id_number" required>
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
                            <input type="text" id="appointment-type" name="appointment_type" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <input type="text" id="role" name="role" required>
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
                            <th>Role</th>
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
                                <td>{{ $appointment->role }}</td>
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
                            <input type="text" id="edit-appointment-type" name="appointment_type" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-role">Role</label>
                            <input type="text" id="edit-role" name="role" required>
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
            document.getElementById('edit-role').value = appointment.children[5].innerText;
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
