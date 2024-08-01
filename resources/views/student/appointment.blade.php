<x-app-layout>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-top: 40px;
            margin-left: 75px;
            transition: margin-left 0.3s ease-in-out;
            overflow-y: auto;
            max-height: auto;
        }

        .sidebar:hover ~ .main-content {
            margin-left: 250px;
        }

        .appointment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
        }

        .appointment-table th,
        .appointment-table td {
            padding: 10px;
            text-align: left;
        }

        .appointment-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .appointment-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .appointment-table td {
            border-bottom: 1px solid #ddd;
        }

        .appointment-table tr:last-child td {
            border-bottom: none;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1rem;
        }

        .action-buttons button:hover {
            background-color: #0056b3;
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
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
            animation: fadeIn 0.5s ease-in-out;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
            animation: slideIn 0.5s ease-in-out;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease-in-out;
        }

        .close:hover,
        .close:focus {
            color: black;
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
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .form-group button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1rem;
        }

        .form-group button:hover {
            background-color: #0056b3;
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
    </style>

    <div class="main-content">
        <h1>Appointments</h1>

        <!-- Appointment Table -->
        <table class="appointment-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr id="appointment-row-1">
                    <td>2024-07-25</td>
                    <td>10:00 AM</td>
                    <td>Consultation</td>
                    <td>
                        <div class="action-buttons">
                            <button onclick="openRescheduleModal(1)">Reschedule</button>
                            <button onclick="openReasonModal(1)">Cancel</button>
                        </div>
                    </td>
                </tr>
                <tr id="appointment-row-2">
                    <td>2024-07-26</td>
                    <td>11:00 AM</td>
                    <td>Check-up</td>
                    <td>
                        <div class="action-buttons">
                            <button onclick="openRescheduleModal(2)">Reschedule</button>
                            <button onclick="openReasonModal(2)">Cancel</button>
                        </div>
                    </td>
                </tr>
                <tr id="appointment-row-3">
                    <td>2024-07-27</td>
                    <td>02:00 PM</td>
                    <td>Follow-up</td>
                    <td>
                        <div class="action-buttons">
                            <button onclick="openRescheduleModal(3)">Reschedule</button>
                            <button onclick="openReasonModal(3)">Cancel</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Reschedule Modal -->
    <div id="reschedule-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeRescheduleModal()">&times;</span>
            <h2>Reschedule Appointment</h2>
            <form id="reschedule-form">
                @csrf
                <input type="hidden" id="reschedule-appointment-id" name="id">
                <div class="form-group">
                    <label for="reschedule-appointment-date">New Date</label>
                    <input type="date" id="reschedule-appointment-date" name="appointment_date" required>
                </div>
                <div class="form-group">
                    <label for="reschedule-appointment-time">New Time</label>
                    <input type="time" id="reschedule-appointment-time" name="appointment_time" required>
                </div>
                <div class="form-group">
                    <button type="button" onclick="rescheduleAppointment()">Reschedule</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reason Modal -->
    <div id="reason-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeReasonModal()">&times;</span>
            <h2>Reason for Cancellation</h2>
            <form id="reason-form">
                @csrf
                <input type="hidden" id="reason-appointment-id" name="id">
                <div class="form-group">
                    <label for="appointment_reason">Reason</label>
                    <textarea id="appointment_reason" name="appointment_reason" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <button type="button" onclick="submitReason()">Submit Reason</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showNotification(message) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
        }

        function openRescheduleModal(id) {
            document.getElementById('reschedule-appointment-id').value = id;
            document.getElementById('reschedule-modal').style.display = 'block';
        }

        function closeRescheduleModal() {
            document.getElementById('reschedule-modal').style.display = 'none';
        }

        function rescheduleAppointment() {
            const form = document.getElementById('reschedule-form');
            const id = form.elements['id'].value;
            const date = form.elements['appointment_date'].value;
            const time = form.elements['appointment_time'].value;

            if (date && time) {
                const appointmentRow = document.getElementById(`appointment-row-${id}`);
                appointmentRow.cells[0].innerText = date;
                appointmentRow.cells[1].innerText = time;
                showNotification('Appointment rescheduled successfully!');
                closeRescheduleModal();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please fill in all fields',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        }

        function openReasonModal(id) {
            document.getElementById('reason-appointment-id').value = id;
            document.getElementById('reason-modal').style.display = 'block';
        }

        function closeReasonModal() {
            document.getElementById('reason-modal').style.display = 'none';
        }

        function submitReason() {
            const form = document.getElementById('reason-form');
            const id = form.elements['id'].value;
            const reason = form.elements['appointment_reason'].value;

            if (reason) {
                const appointmentRow = document.getElementById(`appointment-row-${id}`);
                if (appointmentRow) {
                    appointmentRow.remove();
                    showNotification('Appointment cancelled successfully!');
                    closeReasonModal();
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please provide a reason for cancellation',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        }
    </script>
</x-app-layout>
