<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif; 
                
        }
        .stats-and-table-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 30px;
    padding: 20px;
    flex-wrap: wrap; /* Fix overlapping on smaller screens */

}

        .main-content {
            margin-top: 30px;
        }

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


 /* Remove the comma here */
.form-container {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    animation: fadeInUp 0.5s ease-in-out;
    width: 100%;
    max-width: 500px;
    box-sizing: border-box;
}


        .form-group {
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .form-group label {
            flex: 1 1 100%;
            margin-bottom: 5px;
            font-size: 1rem;
        }

        .form-group input,
        .form-group select {
            flex: 1 1 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .form-group .search-btn {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            margin-left: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1rem;
        }

        .form-group .search-btn:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .form-group .search-btn:active {
            transform: scale(0.95);
        }

        .appointment-table {
            animation: fadeInUp 0.5s ease-in-out;
            width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
        }

        .appointment-table th,
        .appointment-table td {
            padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: left;
    color: #333;
        }

        .appointment-table th {
            background-color: #f5f5f5;
            color: #333;
            font-weight: 600;
            border-bottom: 1px solid #ddd;
        }

        .appointment-table td {
            border-bottom: 1px solid #eee;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .action-buttons button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            transform-origin: center; /* Ensures smooth scaling from the center */
            font-size: 1rem;
        }

        .action-buttons button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .action-buttons button:active {
            transform: scale(0.95);
        }

        .calendar-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
            flex: 1.5;
            margin-left: 20px;
            width: 90%;
        }

        .calendar-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .calendar-controls button {
            background-color: #00d1ff;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1rem;
        }

        .calendar-controls button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .calendar {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .calendar th,
        .calendar td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out; /* Smooth transition */

        }

        .calendar th {
            background-color: #00d1ff;
            color: white;
        }

        .calendar td.active {
            background-color: #00b8e6;
            color: white;
        }

        .calendar td:hover {
            background-color: #f0f8ff;
        }
        .chart-container {
    flex: 1;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.chart-container h2 {
    text-align: center;
    color: #0056b3;
    margin-bottom: 20px;
}
/* Appointment List container (Table section) */
.appointment-list-container {
    flex: 2;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.appointment-list-container h2 {
    text-align: center;
    color: #0056b3;
    margin-bottom: 20px;
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
        .appointment-preview {
            animation: fadeInUp 0.5s ease-in-out;
    background-color: #f8f9fa;
    color: #0056b3;
    font-size: 0.8rem;
    padding: 5px;
    border-radius: 5px;
    margin-top: 5px;
}
.appointment-preview p {
    margin: 0;
}
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.6); /* Dark background with opacity */
    animation: fadeIn 0.4s ease-in-out;
}
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}


.modal-content {
    background-color: white;
    margin: 10% auto;
    padding: 20px;
    border-radius: 12px;
    max-width: 600px;
    width: 90%;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    animation: slideIn 0.4s ease-in-out;
    overflow-y: auto;
    max-height: 80%;
}
@keyframes slideIn {
    from {
        transform: translateY(-50px);
    }
    to {
        transform: translateY(0);
    }
}

.modal h2 {
    margin-top: 0;
    font-size: 1.8rem;
    text-align: center;
    color: #0056b3;
}

/* Close Button */
.close {
    color: #999;
    float: right;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover, .close:focus {
    color: #333;
    text-decoration: none;
    cursor: pointer;
}

/* Appointments List Styles */
.appointments-container {
    padding: 15px 0;
}

#appointments-list {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

#appointments-list li {
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 10px;
    margin-bottom: 10px;
    transition: transform 0.2s ease-in-out;
}

#appointments-list li:hover {
    background-color: #f0f8ff;
    transform: scale(1.02);
}

/* Appointment Text */
#appointments-list li p {
    margin: 0;
    font-size: 1rem;
    color: #333;
}

#appointments-list li p span {
    font-weight: bold;
    color: #0056b3;
}

/* Custom Scrollbar for Modal Content */
.modal-content::-webkit-scrollbar {
    width: 8px;
}

.modal-content::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 5px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background-color: #888;
}
/* Add Appointment Button Styles */
.add-appointment-btn {
    background-color: #00d1ff;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: bold;
    transition: background-color 0.3s, box-shadow 0.3s, transform 0.2s ease-in-out;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.add-appointment-btn:hover {
    background-color: #00d1ff;
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
    transform: scale(1.05);
}

.add-appointment-btn:active {
    transform: scale(0.98);
    box-shadow: 0 3px 4px rgba(0, 0, 0, 0.2);
}

    </style>

    <main class="main-content">
    <div class="tabs">
            <div class="tab active" onclick="showTab('doctors-appointment-calendar')">Doctors, Add Appointment & Calendar</div>
            <div class="tab" onclick="showTab('stats-appointment-list')">Statistics & Appointment List</div>
        </div>

  <!-- Doctors, Add Appointment and Calendar Tab -->
<div id="doctors-appointment-calendar" class="tab-content active">
    <div style="display: flex; justify-content: space-between; width: 100%; gap: 20px;">
        <!-- Form Section -->
        <div class="form-container" style="flex: 1;">
            <h2>Add Appointment</h2>
            <form id="add-form">
                @csrf
                <div class="form-group">
                    <label for="id-number">ID Number</label>
                    <div style="display: flex; align-items: center;">
                        <input type="text" id="id-number" name="id_number" required maxlength="7">
                        <button type="button" class="search-btn" onclick="fetchPatientName()">Search</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="patient-name">Patient Name</label>
                    <input type="text" id="patient-name" name="patient_name" required>
                </div>
                <div class="form-group input-row">
                    <div>
                        <label for="appointment-date">Appointment Date</label>
                        <input type="date" id="appointment-date" name="appointment_date" required>
                    </div>
                    <div>
                        <label for="appointment-time">Appointment Time</label>
                        <input type="time" id="appointment-time" name="appointment_time" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="appointment-type">Appointment Type</label>
                    <select id="appointment-type" name="appointment_type" required>
                        <option value="Dr. Isnani">Dr. Nurmina Isnani</option>
                        <option value="Dr. Sarah Uy Gan">Dr. Sarah Uy Gan</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="button" class="add-appointment-btn" onclick="addAppointment()">Add Appointment</button>
                </div>
            </form>
        </div>

        <!-- Calendar Section -->
        <div class="calendar-container" style="flex: 1.5;">
            <h2>Appointment Calendar</h2>
            <div class="calendar-controls">
                <button onclick="changeMonth(-1)">Previous</button>
                <span id="calendar-month-year"></span>
                <button onclick="changeMonth(1)">Next</button>
            </div>
            <table class="calendar">
                <thead>
                    <tr>
                        <th>Sun</th>
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                    </tr>
                </thead>
                <tbody id="calendar-body">
                    <!-- Dynamically generated calendar rows go here -->
                </tbody>
            </table>
        </div>

        <!-- Doctors Profile Section -->
        <div class="form-container" style="flex: 1;">
        <div id="doctors-section" style="flex: 1;">
            <h2>Our Doctors</h2>
            <div class="content-row" style="display: flex; flex-direction: column;">
                <div class="profile-box">
                    <img src="https://img.icons8.com/?size=100&id=9570&format=png&color=000000" alt="Dr. Isnani">
                    <div class="profile-info">
                        <h2>Dr. Nurmina Isnani</h2>
                        <p>General Physician</p>
                    </div>
                </div>
                <div class="profile-box">
                    <img src="https://img.icons8.com/?size=100&id=9570&format=png&color=000000" alt="Dr. Nurmina">
                    <div class="profile-info">
                        <h2>Dr. Sarah Uy Gan</h2>
                        <p>School Dentist</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

        <!-- Statistics and Appointment List Tab -->
        <div id="stats-appointment-list" class="tab-content">
        <div class="stats-and-table-container">
            <div class="chart-container">
                <h2>Statistics</h2>
                <canvas id="appointmentsChart"></canvas>
            </div>
            <div class="appointment-list-container">
            <h2>Appointment List</h2>
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
                        <input type="text" id="edit-id-number" name="id_number" required maxlength="7">
                    </div>
                    <div class="form-group">
                        <label for="edit-patient-name">Patient Name</label>
                        <input type="text" id="edit-patient-name" name="patient_name" required>
                    </div>
                    <div class="form-group input-row">
                        <div>
                            <label for="edit-appointment-date">Appointment Date</label>
                            <input type="date" id="edit-appointment-date" name="appointment_date" required>
                        </div>
                        <div>
                            <label for="edit-appointment-time">Appointment Time</label>
                            <input type="time" id="edit-appointment-time" name="appointment_time" required>
                        </div>
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

        <!-- Preview Appointments Modal -->
        <div id="preview-modal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closePreviewModal()">&times;</span>
                <h2>Appointments on <span id="preview-date"></span></h2>
                <ul id="appointments-list">
                    <!-- Appointments will be dynamically inserted here -->
                </ul>
            </div>
        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        const idNumber = document.getElementById('id-number').value;

        fetch(`/admin/appointment/fetch-patient-name/${idNumber}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error,
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    document.getElementById('patient-name').value = `${data.first_name} ${data.last_name}`;
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Patient name fetched successfully!',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching patient name:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred.',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
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



function closePreviewModal() {
    document.getElementById('preview-modal').style.display = 'none';
}

    function addAppointment() {
        const form = document.getElementById('add-form');
        const formData = new FormData(form);
        const patientName = document.getElementById('patient-name').value;
        if (!patientName) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Patient name cannot be empty.',
                timer: 3000,
                showConfirmButton: false
            });
            return; // Prevent form submission
        }

        fetch('{{ route("admin.appointment.add") }}', {
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

        fetch(`{{ url('/admin/appointment/update/') }}/${id}`, {
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
                fetch(`{{ url('/admin/appointment/delete/') }}/${id}`, {
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

    // Calendar functionalities
    const currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    function renderCalendar(month, year) {
    console.log("Rendering calendar for:", month + 1, year);

    const calendarBody = document.getElementById('calendar-body');
    calendarBody.innerHTML = '';
    const monthYearText = document.getElementById('calendar-month-year');
    const firstDay = new Date(year, month).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    monthYearText.textContent = `${monthNames[month]} ${year}`;
    let date = 1;

    for (let i = 0; i < 6; i++) { // 6 weeks (rows) to cover all possible days
        let row = document.createElement('tr');
        for (let j = 0; j < 7; j++) { // 7 days (columns) in a week
            let cell = document.createElement('td');
            if (i === 0 && j < firstDay) {
                cell.appendChild(document.createTextNode('')); // Empty cells before the first day of the month
            } else if (date > daysInMonth) {
                break; // Stop adding dates once we pass the last day of the month
            } else {
                let selectedDate = date; // Capture the correct date for this iteration

                cell.textContent = selectedDate;

                // Set up the click event to fetch and display appointments for the selected date
                cell.onclick = () => {
                    if (selectedDate <= daysInMonth) {
                        console.log("Valid cell clicked:", selectedDate, month + 1, year);
                        openPreviewModal(selectedDate, month, year);
                    } else {
                        console.error(`Invalid date clicked: ${selectedDate} for month ${month + 1}, ${year}`);
                    }
                };

                // Highlight today's date if it's the current month and year
                const today = new Date();
                if (selectedDate === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
                    cell.classList.add('active');
                }

                date++;
            }
            row.appendChild(cell);
        }
        calendarBody.appendChild(row);
    }
}

function openPreviewModal(date, month, year) {
    console.log("openPreviewModal called for date:", date, "month:", month + 1, "year:", year);

    const daysInMonth = new Date(year, month + 1, 0).getDate();
    if (date > daysInMonth) {
        console.error('Invalid date:', date, 'for month:', month + 1, 'in year:', year);
        return;
    }

    const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;

    document.getElementById('preview-date').innerText = formattedDate;

    fetch(`/admin/appointments-by-date?date=${formattedDate}`)
        .then(response => response.json())
        .then(data => {
            const appointmentsList = document.getElementById('appointments-list');
            appointmentsList.innerHTML = ''; // Clear previous appointments

            if (data.appointments && data.appointments.length > 0) {
                // Display each appointment
                data.appointments.forEach(appointment => {
                    const li = document.createElement('li');
                    li.innerText = `${appointment.patient_name} - ${appointment.appointment_time} (${appointment.appointment_type})`;
                    appointmentsList.appendChild(li);
                });
            } else {
                // No appointments found for the selected date
                const li = document.createElement('li');
                li.innerText = 'No appointments for this day.';
                appointmentsList.appendChild(li);
            }

            // Ensure the modal is visible
            const modal = document.getElementById('preview-modal');
            modal.style.display = 'block'; // Show the modal
        })
        .catch(error => {
            console.error('Error fetching appointments for the day:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load appointments.',
                timer: 3000,
                showConfirmButton: false
            });
        });
}


function closePreviewModal() {
    const modal = document.getElementById('preview-modal');
    modal.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    renderCalendar(currentMonth, currentYear);
});

function changeMonth(direction) {
        currentMonth += direction;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        } else if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar(currentMonth, currentYear);
    }
     // Chart.js statistics for appointments
     const ctx = document.getElementById('appointmentsChart').getContext('2d');
        const appointmentsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total', 'Upcoming', 'Completed', 'Dr. Isnani', 'Dr. Gan'],
                datasets: [{
                    label: 'Number of Appointments',
                    data: [{{ $totalAppointments }}, {{ $upcomingAppointments }}, {{ $completedAppointments }}, {{ $drIsnaniAppointments }}, {{ $drGanAppointments }}],
                    backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'],
                    borderColor: ['rgba(75, 192, 192, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
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
</script>


</x-app-layout>