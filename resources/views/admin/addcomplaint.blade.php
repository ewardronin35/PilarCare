<x-app-layout>
    <style>
        .main-content {
            margin-left: 80px;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            animation: fadeInBackground 1s ease-in-out;
        }

        .form-container {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
            width: 100%;
            box-sizing: border-box;
            overflow-y: auto;
            max-height: auto;
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            width: 100%;
            font-size: 16px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: calc(50% - 10px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .form-group textarea {
            width: 100%;
            padding-left: 20px;
        }

        .form-group button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-group button:hover {
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

        .input-wrapper {
            position: relative;
            width: calc(50% - 10px);
            margin-bottom: 20px;
        }

        .input-wrapper::before {
            content: attr(data-icon);
            position: absolute;
            left: 10px;
            top: 70%;
            transform: translateY(-50%);
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: black;
            z-index: 2;
            font-size: 20px;
        }

        .input-wrapper input,
        .input-wrapper select {
            width: 90%;
            padding: 15px;
            padding-left: 35px;
        }

        .textarea-wrapper {
            position: relative;
            width: 100%;
        }

        .textarea-wrapper label {
            display: flex;
            align-items: center;
        }

        .textarea-wrapper label::before {
            content: "\f0f9";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: black;
            margin-right: 10px;
            font-size: 20px;
        }

        .textarea-wrapper textarea {
            width: 100%;
            padding-left: 15px;
        }

        .search-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-container .input-wrapper {
            width: auto;
            flex: 1;
        }

        .search-container button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-bottom: 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-container button:hover {
            background-color: #00b8e6;
        }

        .complaint-status {
            display: none;
            margin-top: 20px;
            text-align: center;
        }

        .complaint-status.active {
            display: block;
        }

        .status {
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .status-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .status-buttons button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #mark-completed {
            background-color: #28a745;
            color: white;
        }

        #mark-not-completed {
            background-color: #dc3545;
            color: white;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            width: 100%;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info .username {
            margin-right: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .notification-icon {
            margin-right: 20px;
            position: relative;
        }

        .notification-dropdown {
            display: none;
            position: absolute;
            top: 50px;
            right: 10px;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .notification-dropdown.active {
            display: block;
        }

        .notification-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background-color: #f9f9f9;
            transform: translateX(10px);
        }

        .notification-item .icon {
            margin-right: 10px;
        }

        .notification-header {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            font-weight: bold;
        }
    </style>

    <div class="form-container" id="complaint-form-container">
        <h2>Health Complaint</h2>
        <div class="search-container">
            <div class="input-wrapper" data-icon="&#xf007;">
                <input type="text" id="id_number" name="id_number" placeholder="Enter ID Number">
            </div>
            <button type="button" onclick="fetchStudentData()">Search</button>
        </div>
        <form id="complaint-form" action="{{ route('admin.complaint.store') }}" method="POST">
            @csrf
            <input type="hidden" name="role" value="{{ strtolower(Auth::user()->role) }}">
            <input type="hidden" name="student_type" value="TED">
            <input type="hidden" name="year" value="{{ date('Y') }}">
            <div class="form-group">
                <div class="input-wrapper" data-icon="&#xf007;">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="input-wrapper" data-icon="&#xf254;">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" required>
                </div>
            </div>
            <div class="form-group">
                <div class="input-wrapper" data-icon="&#xf133;">
                    <label for="birthdate">Birthdate</label>
                    <input type="date" id="birthdate" name="birthdate" required>
                </div>
                <div class="input-wrapper" data-icon="&#xf095;">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number" required>
                </div>
            </div>
            <div class="form-group">
                <div class="input-wrapper" data-icon="&#xf0f0;">
                    <label for="pain_assessment">Pain Assessment (1 to 10)</label>
                    <select id="pain_assessment" name="pain_assessment" required>
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="input-wrapper" data-icon="&#xf0f1;">
                    <label for="medicine_given">Medicine Given</label>
                    <select id="medicine_given" name="medicine_given" required></select>
                </div>
            </div>
            <div class="form-group">
                <div class="textarea-wrapper">
                    <label for="sickness_description">Description of Sickness</label>
                    <textarea id="sickness_description" name="sickness_description" rows="4" required></textarea>
                </div>
            </div>
            <div class="form-group">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>

    <div class="complaint-status" id="complaint-status-container">
        <span class="status">Status: <span id="complaint-status">Not Yet Done</span></span>
        <div class="status-buttons">
            <button id="mark-completed" onclick="updateStatus('Completed')">Completed</button>
            <button id="mark-not-completed" onclick="updateStatus('Not yet done')">Not Yet Done</button>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchAvailableMedicines();
        });

        function fetchAvailableMedicines() {
            fetch('{{ route('admin.inventory.available-medicines') }}')
            .then(response => response.json())
            .then(data => {
                console.log('Medicines:', data); // Debugging log
                const medicineSelect = document.getElementById('medicine_given');
                medicineSelect.innerHTML = ''; // Clear existing options
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

        function fetchStudentData() {
            const idNumber = document.getElementById('id_number').value;
            if (!idNumber) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please enter an ID number.'
                });
                return;
            }

            fetch(`/complaint/student/${idNumber}`)
            .then(response => response.json())
            .then(data => {
                console.log('Student data:', data); // Debugging log
                if (data.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error
                    });
                } else {
                    document.getElementById('name').value = data.name;
                    document.getElementById('age').value = data.age;
                    document.getElementById('birthdate').value = data.birthdate;
                    document.getElementById('contact_number').value = data.contact_number;
                    // Add other fields as necessary
                }
            })
            .catch(error => {
                console.error('Error fetching student data:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again later.'
                });
            });
        }

        document.getElementById('complaint-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('{{ route("admin.complaint.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(JSON.stringify(errorData));
                    });
                }
                return response.json();
            })
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message,
                }).then(() => {
                    document.getElementById('complaint-form-container').style.display = 'none';
                    document.getElementById('complaint-status-container').classList.add('active');
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again. ' + error.message
                });
            });
        });

        function updateStatus(status) {
            const complaintId = /* Set this to the correct complaint ID */;
            fetch('{{ url("/admin/complaint/update-status") }}/' + complaintId, {
                method: 'POST',
                body: JSON.stringify({ status: status }),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('complaint-status').innerText = status;
                    if (status === 'Completed') {
                        document.getElementById('complaint-form').reset();
                        document.getElementById('complaint-form-container').style.display = 'block';
                        document.getElementById('complaint-status-container').classList.remove('active');
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Could not update status. Please try again.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again later.'
                });
            });
        }
    </script>
</x-app-layout>
