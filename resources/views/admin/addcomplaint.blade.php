<x-app-layout>
    <style>
        .main-content {
            margin-left: 80px;
            font-size: 16px;
            margin-top: 100px;
        }

        .form-container {
            background-color: #f9f9f9;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
            width: 80%;
            box-sizing: border-box;
            overflow-y: auto;
            max-height: auto;
            margin: 20px auto;
        }

        .form-group {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
            justify-content: space-between;
        }

        .form-group label {
            display: flex;
            align-items: center;
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
            border-radius: 50px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .form-group textarea {
            width: 100%;
            padding-left: 20px;
            border-radius: 20px;
        }

        .form-group button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
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
        }

        .input-wrapper input,
        .input-wrapper select {
            width: 100%;
            padding: 15px;
            padding-left: 15px;
            border-radius: 50px;
        }

        .textarea-wrapper {
            position: relative;
            width: 100%;
        }

        .textarea-wrapper textarea {
            width: 100%;
            padding-left: 15px;
            border-radius: 20px;
        }

        .search-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            width: 60%;
            margin: 0 auto;
        }

        .search-container .input-wrappers {
            width: auto;
            flex: 1;
        }

        .search-container button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 50px;
            margin-left: 30px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-container button:hover {
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
            animation: fadeInUp 0.5s ease-in-out;
        }

        .tab:hover {
            background-color: #00d2ff;
            color: white;
        }

        .confinement-status {
            margin-top: 20px;
            text-align: center;
        }

        .confinement-status label {
            font-size: 16px;
            margin-right: 10px;
        }

        .confinement-options {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .confinement-options input[type="radio"] {
            margin-right: 5px;
        }
    </style>

    <div class="tabs">
        <div class="tab active" data-tab="add-complaint-tab">Add Complaint</div>
        <a href="{{ route('admin.complaint') }}" class="tab">Complaint List</a>
    </div>

    <div id="add-complaint-tab" class="tab-content active">
        <div class="form-container" id="complaint-form-container">
            <h2>Health Complaint</h2>
            <div class="search-container">
                <div class="input-wrapper">
                    <input type="text" id="id_number" name="id_number" placeholder="Enter ID Number">
                </div>
                <button type="button" onclick="fetchStudentData()">Search</button>
            </div>
            <form id="complaint-form" action="{{ route('admin.complaint.store') }}" method="POST">
                @csrf
                <input type="hidden" name="role" id="role" value="">
                <input type="hidden" name="student_type" value="TED">
                <input type="hidden" name="year" value="{{ date('Y') }}">
                <input type="hidden" name="id_number" id="hidden_id_number" value="">
                <div class="form-group">
                    <div class="input-wrapper">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                    </div>
                    <div class="input-wrapper">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-wrapper">
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" required>
                    </div>
                    <div class="input-wrapper">
                        <label for="birthdate">Birthdate</label>
                        <input type="date" id="birthdate" name="birthdate" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-wrapper">
                        <label for="contact_number">Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number" required>
                    </div>
                    <div class="input-wrapper">
                        <label for="pain_assessment">Pain Assessment (1 to 10)</label>
                        <select id="pain_assessment" name="pain_assessment" required>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-wrapper">
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

            fetch(`/admin/complaint/student/${idNumber}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error
                        });
                    } else {
                        document.getElementById('first_name').value = data.first_name || '';
                        document.getElementById('last_name').value = data.last_name || '';
                        document.getElementById('age').value = data.age || '';
                        document.getElementById('birthdate').value = data.birthdate || '';
                        document.getElementById('contact_number').value = data.contact_number || '';
                        document.getElementById('role').value = data.role || '';
                        document.getElementById('hidden_id_number').value = data.id_number || '';

                        Swal.fire({
                            icon: 'success',
                            title: 'Student Data Fetched',
                            text: 'Student data successfully fetched.'
                        });
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
            const medicineGiven = document.getElementById('medicine_given').value;

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
                    document.getElementById('complaint-form').reset(); // Reset the form
                    fetchAvailableMedicines(); // Refresh available medicines
                    updateInventory(medicineGiven); // Update inventory
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

        function updateInventory(medicineName) {
            fetch('/admin/inventory/update-quantity', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ medicine: medicineName })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Inventory updated:', data);
            })
            .catch(error => {
                console.error('Error updating inventory:', error);
            });
        }
    </script>
</x-app-layout>
