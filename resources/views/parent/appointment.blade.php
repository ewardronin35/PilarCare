<x-app-layout>
    <style>
        .form-container {
            margin-top: 20px;
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
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #00b8e6;
        }

        .appointment-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .appointment-table th, .appointment-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .appointment-table th {
            background-color: #00d1ff;
            color: white;
        }

        .appointment-table td input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
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
    </style>

    <div class="form-container">
        <h2>Schedule Appointment</h2>
        <form method="POST" action="{{ route(strtolower(Auth::user()->role) . '.appointment.add') }}">
            @csrf
            <div class="form-group">
                <label for="patient-id">Patient ID</label>
                <input type="text" id="patient-id" name="patient_id" required>
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
                <button type="submit">Schedule Appointment</button>
            </div>
        </form>
    </div>

    
        <tbody>
            @foreach ($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->patient_id }}</td>
                    <td>{{ $appointment->patient_name }}</td>
                    <td>{{ $appointment->appointment_date }}</td>
                    <td>{{ $appointment->appointment_time }}</td>
                    <td>
                        <div class="action-buttons">
                            <button onclick="document.getElementById('update-form-{{ $appointment->id }}').style.display='block'">Edit</button>
                            <form method="POST" action="{{ route(strtolower(Auth::user()->role) . '.appointment.delete', $appointment->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</button>
                            </form>
                        </div>
                        <div id="update-form-{{ $appointment->id }}" style="display:none" class="form-container">
                            <h2>Update Appointment</h2>
                            <form method="POST" action="{{ route(strtolower(Auth::user()->role) . '.appointment.update', $appointment->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="patient-id-{{ $appointment->id }}">Patient ID</label>
                                    <input type="text" id="patient-id-{{ $appointment->id }}" name="patient_id" value="{{ $appointment->patient_id }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="patient-name-{{ $appointment->id }}">Patient Name</label>
                                    <input type="text" id="patient-name-{{ $appointment->id }}" name="patient_name" value="{{ $appointment->patient_name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="appointment-date-{{ $appointment->id }}">Appointment Date</label>
                                    <input type="date" id="appointment-date-{{ $appointment->id }}" name="appointment_date" value="{{ $appointment->appointment_date }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="appointment-time-{{ $appointment->id }}">Appointment Time</label>
                                    <input type="time" id="appointment-time-{{ $appointment->id }}" name="appointment_time" value="{{ $appointment->appointment_time }}" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit">Update Appointment</button>
                                    <button type="button" onclick="document.getElementById('update-form-{{ $appointment->id }}').style.display='none'">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        let complaintIndex = 1;
        function addComplaintRow() {
            const tableBody = document.getElementById('complaints-table-body');
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td><input type="datetime-local" name="complaints[${complaintIndex}][datetime]" required></td>
                <td><input type="text" name="complaints[${complaintIndex}][complaint]" required></td>
                <td><input type="text" name="complaints[${complaintIndex}][management]" required></td>
                <td><input type="text" name="complaints[${complaintIndex}][remarks]" required></td>
            `;

            tableBody.appendChild(newRow);
            complaintIndex++;
        }

        document.getElementById('notification-icon').addEventListener('click', function() {
            var dropdown = document.getElementById('notification-dropdown');
            dropdown.classList.toggle('active');
        });

        document.addEventListener('click', function(event) {
            var dropdown = document.getElementById('notification-dropdown');
            var icon = document.getElementById('notification-icon');
            if (!dropdown.contains(event.target) && !icon.contains(event.target)) {
                dropdown.classList.remove('active');
                icon.classList.remove('active');
            }
        });
    </script>
</x-app-layout>
