<x-app-layout>
    <style>
        .main-content {
           
            margin-top: 90px;
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
        <!-- Add icons using Font Awesome -->
        <div class="tab active" data-tab="add-complaint-tab">
            <i class="fas fa-plus-circle"></i> Add Complaint
        </div>
        <a href="{{ route('admin.complaint') }}" class="tab">
            <i class="fas fa-list-alt"></i> Complaint List
        </a>
    </div>

    <div id="add-complaint-tab" class="tab-content active">
        <div class="form-container" id="complaint-form-container">
            <h2><i class="fas fa-medkit"></i> Health Complaint</h2>

            <!-- Search section -->
            <div class="search-container">
                <div class="input-wrapper">
                    <label for="id_number">
                        <i class="fas fa-id-card"></i> ID Number
                    </label>
                    <input type="text" id="id_number" name="id_number" placeholder="Enter ID Number">
                </div>
                <button type="button" onclick="fetchPersonData()">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>

            <form id="complaint-form" action="{{ route('admin.complaint.store') }}" method="POST">
                @csrf
                <input type="hidden" name="role" id="role" value="">
                <input type="hidden" name="student_type" value="TED">
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
                </div>

                <!-- Submit button -->
                <div class="form-group">
                    <button type="submit">
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchAvailableMedicines();
        });

        function calculateAge() {
            const birthdate = new Date(document.getElementById('birthdate').value);
            const today = new Date();
            let age = today.getFullYear() - birthdate.getFullYear();
            const m = today.getMonth() - birthdate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }
            document.getElementById('age').value = age;
        }

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

    fetch(`/admin/complaint/person/${idNumber}`)
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.error || 'Unknown error');
                });
            }
            return response.json();
        })
        .then(data => {
            // Fill in the fetched data into the form
            document.getElementById('first_name').value = data.first_name || '';
            document.getElementById('last_name').value = data.last_name || '';
            document.getElementById('age').value = data.age || '';
            document.getElementById('birthdate').value = data.birthdate || '';
            document.getElementById('personal_contact_number').value = data.personal_contact_number || ''; // Ensure this line exists
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                    }).then(() => {
                        document.getElementById('complaint-form').reset(); // Reset the form
                        fetchAvailableMedicines(); // Refresh available medicines
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.'
                });
            });
        });
    </script>
</x-app-layout>
