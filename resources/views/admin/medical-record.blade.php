<x-app-layout>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Poppins', sans-serif;
        }
        

        .container {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }

        .main-content {
            margin-left: 80px;
            margin-top: 20px;
            width: calc(100% - 80px);
            padding: 20px;
        }
        .tab.hidden {
    display: none;
}

        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
            
        }

        .tab-buttons button {
            padding: 10px 20px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 1.1rem;
            color: #007bff;
            font-weight: bold;
            transition: color 0.3s, border-bottom 0.3s;
            margin-bottom: -2px;
            border-bottom: 3px solid transparent;
        }

        .tab-buttons button.active {
            color: #007bff;
            border-bottom: 3px solid #007bff;
        }

        .forms-container {
            display: flex;
            gap: 20px;
            width: 95%;
            height: 80%;
            margin: 0 auto;
        }

        .form-container {
            flex: 1;
            background-color: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            max-height: 75vh;
            border: 1px solid #eaeaea;
        }

        .form-header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }

        .form-header h2 {
            color: #007bff;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .profile-picture {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-picture img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-bottom: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .profile-picture button {
            background-color: #007bff;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 0.9rem;
        }

        .profile-picture button:hover {
            background-color: #0056b3;
        }
        .profile-picture input[type="file"] {
    display: none; /* Hide the default file input */
}
        .form-group-inline {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .form-group label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            resize: none;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            color: #333;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-group textarea {
            resize: none;
            height: 100px;
        }

        .form-section {
            margin-top: 20px;
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
 

.tab {
    opacity: 0;
    transition: opacity 0.4s ease; /* Smooth transition for opacity */
}

.tab.active {
    opacity: 1;
    display: block; /* Show the active tab */
}
        .checkbox-group label {
            display: flex;
            align-items: center;
            font-weight: 500;
            color: #555;
            gap: 5px;
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
        }
        .physical-exam-container {
    position: sticky; /* Keep it in place as the user scrolls */
    top: 20px; /* Space from the top of the viewport */
    flex: 1; /* Flex-grow to take space */
    background-color: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid #eaeaea;
    max-height: 75vh;
    overflow-y: auto; /* Scroll within the container if content overflows */
}
        .form-group button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            font-size: 1.1rem;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }
        .bmi-display {
    font-size: 1.2rem;
    font-weight: bold;
    color: #007bff;
    margin-top: 10px;
}

        .bmi-result {
            margin-top: 10px;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 0.95rem;
        }

        .history-table th, .history-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .history-table th {
            background-color: #f7f7f7;
            font-weight: 600;
        }

        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-bar input[type="text"] {
            width: 350px;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-size: 1rem;
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-left: 10px;
            transition: background-color 0.3s;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }
    </style>

    <div class="main-content">
        <div class="tabs">
            <div class="tab-buttons">
                <button id="medical-tab" class="active" onclick="showTab('medical')">Medical Record</button>
                <button id="history-tab" onclick="showTab('history')">Health History</button>
                </div>
        </div>

        <form method="GET" action="{{ route('admin.medical-record.search') }}" id="search-form">
            <div class="search-bar">
                <input type="text" placeholder="Search Records..." id="search-input">
                <button id="search-button">Search</button>
            </div>
        </form>

        <div id="medical" class="tab forms-container">
        <!-- Profile Information -->
            <div class="form-container">
                <div class="form-header">
                    <h2>Patient Information</h2>
                </div>
                <div class="profile-picture">
    <img id="profile-picture-preview" 
         src="{{ isset($record) && $record->profile_picture ? asset('storage/' . $record->profile_picture) : asset('images/pilarLogo.jpg') }}" 
         alt="Profile Picture">
    <button type="button" id="profile-picture-button" class="button">Choose Profile Picture</button>
    <input type="file" id="profile-picture-upload" name="profile_picture" accept="image/*" style="display:none;">
</div>

                <form method="POST" action="{{ route('student.medical-record.store') }}" enctype="multipart/form-data" id="medical-record-form">
                    @csrf
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ $record->name ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="birthdate">Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" value="{{ $record->birthdate ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" id="age" name="age" value="{{ $age ?? '' }}" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" value="{{ $record->address ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="father-name">Father's Name</label>
                            <input type="text" id="father-name" name="father_name" value="{{ $record->father_name ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="mother-name">Mother's Name</label>
                            <input type="text" id="mother-name" name="mother_name" value="{{ $record->mother_name ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="personal-contact-number">Personal Contact Number</label>
                            <input type="text" id="personal-contact-number" name="personal_contact_number" value="{{ $record->personal_contact_number ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="emergency-contact-number">Emergency Contact Number</label>
                            <input type="text" id="emergency-contact-number" name="emergency_contact_number" value="{{ $record->emergency_contact_number ?? '' }}" required>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Medical Information -->
            <div class="form-container">
                <div class="form-header">
                    <h2>Medical Information</h2>
                </div>
                <form method="POST" action="">
                    @csrf
                    <div class="form-section">
                        <h2>Medical History</h2>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="past-illness">Past Illnesses/Injuries</label>
                            <input type="text" id="past-illness" name="past_illness" value="{{ $record->past_illness ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="chronic-conditions">Chronic Conditions</label>
                            <input type="text" id="chronic-conditions" name="chronic_conditions" value="{{ $record->chronic_conditions ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="surgical-history">Surgical History</label>
                            <input type="text" id="surgical-history" name="surgical_history" value="{{ $record->surgical_history ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="family-medical-history">Family Medical History</label>
                            <input type="text" id="family-medical-history" name="family_medical_history" value="{{ $record->family_medical_history ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="allergies">Allergies</label>
                        <input type="text" id="allergies" name="allergies" value="{{ $record->allergies ?? '' }}" required>
                    </div>
                    <div class="form-section">
                        <h2>Medicines OK to give/apply at the clinic</h2>
                        <div class="checkbox-group">
                            <label><input type="checkbox" name="medicines[]" value="Paracetamol" @if(in_array('Paracetamol', json_decode($record->medicines ?? '[]'))) checked @endif> Paracetamol</label>
                            <label><input type="checkbox" name="medicines[]" value="Ibuprofen" @if(in_array('Ibuprofen', json_decode($record->medicines ?? '[]'))) checked @endif> Ibuprofen</label>
                            <label><input type="checkbox" name="medicines[]" value="Mefenamic Acid" @if(in_array('Mefenamic Acid', json_decode($record->medicines ?? '[]'))) checked @endif> Mefenamic Acid</label>
                            <label><input type="checkbox" name="medicines[]" value="Citirizine/Loratadine" @if(in_array('Citirizine/Loratadine', json_decode($record->medicines ?? '[]'))) checked @endif> Citirizine/Loratadine</label>
                            <label><input type="checkbox" name="medicines[]" value="Camphor + Menthol Liniment" @if(in_array('Camphor + Menthol Liniment', json_decode($record->medicines ?? '[]'))) checked @endif> Camphor + Menthol Liniment</label>
                            <label><input type="checkbox" name="medicines[]" value="PPA" @if(in_array('PPA', json_decode($record->medicines ?? '[]'))) checked @endif> PPA</label>
                            <label><input type="checkbox" name="medicines[]" value="Phenylephrine" @if(in_array('Phenylephrine', json_decode($record->medicines ?? '[]'))) checked @endif> Phenylephrine</label>
                            <label><input type="checkbox" name="medicines[]" value="Antacid" @if(in_array('Antacid', json_decode($record->medicines ?? '[]'))) checked @endif> Antacid</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="button" onclick="temporarySave('medical')">Save</button>
                    </div>
                </form>
            </div>

            <!-- Physical Examination -->
            <div class="form-container">
                <div class="form-header">
                    <h2>Physical Examination</h2>
                </div>
                <form method="POST" action="" id="physical-examination-form">
                    @csrf
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="height">Height (cm)</label>
                            <input type="text" id="height" name="height" required oninput="calculateBMI()">
                        </div>
                        <div class="form-group">
                            <label for="weight">Weight (kg)</label>
                            <input type="text" id="weight" name="weight" required oninput="calculateBMI()">
                        </div>
                    </div>
                    <div class="form-group">
                        <p class="bmi-result">BMI: <span id="bmi-value">N/A</span></p>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="vision">Vision</label>
                            <input type="text" id="vision" name="vision" required>
                        </div>
                        <div class="form-group">
                            <label for="medicine-intake">Medicine Intake</label>
                            <input type="text" id="medicine-intake" name="medicine-intake" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea id="remarks" name="remarks" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="button" class="button" onclick="approveSignature()">MD's Signature</button>
                    </div>
                    <div class="form-group">
                        <button type="button" class="button" onclick="temporarySave('physical')">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="history" class="tab forms-container hidden">
        <div class="form-container">
                <h2>Medical Record History</h2>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Contact Number</th>
                            <th>Illness</th>
                            <th>Surgery</th>
                        </tr>
                    </thead>
                    <tbody id="medical-record-history-body">
                        <!-- Dummy data -->
                        <tr>
                            <td>John Doe</td>
                            <td>25</td>
                            <td>1234567890</td>
                            <td>Flu</td>
                            <td>Appendectomy</td>
                        </tr>
                        <tr>
                            <td>Jane Smith</td>
                            <td>30</td>
                            <td>0987654321</td>
                            <td>Asthma</td>
                            <td>None</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="form-container">
                <h2>Physical Examination History</h2>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Height</th>
                            <th>Weight</th>
                            <th>Vision</th>
                            <th>Medicine Intake</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="physical-examination-history-body">
                        <!-- Dummy data -->
                        <tr>
                            <td>150 cm</td>
                            <td>50 kg</td>
                            <td>20/20</td>
                            <td>No</td>
                            <td>Healthy</td>
                        </tr>
                        <tr>
                            <td>160 cm</td>
                            <td>55 kg</td>
                            <td>20/20</td>
                            <td>Yes</td>
                            <td>Healthy</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function showTab(tabId) {
        const tabs = document.querySelectorAll('.tab');
        
        // Hide all tabs
        tabs.forEach(tab => {
            tab.style.opacity = 0;
            setTimeout(() => {
                tab.classList.add('hidden');
            }, 400); // 400ms for fade-out transition
        });

        // After hiding all tabs, show the selected tab with fade-in effect
        setTimeout(() => {
            const selectedTab = document.getElementById(tabId);
            selectedTab.classList.remove('hidden');
            setTimeout(() => {
                selectedTab.style.opacity = 1; // Fade-in effect
            }, 50); // Delay for smoother transition
        }, 400); // After fade-out completes

        // Remove the active class from all buttons
        document.querySelectorAll('.tab-buttons button').forEach(button => {
            button.classList.remove('active');
        });

        // Add the active class to the clicked button
        document.getElementById(tabId + '-tab').classList.add('active');
    }

    // Ensure the default tab (Medical Record) is shown on page load
    document.addEventListener('DOMContentLoaded', function() {
        showTab('medical');  // Automatically show the "Medical Record" tab on page load
    });

    document.getElementById('profile-picture-button').addEventListener('click', function() {
        document.getElementById('profile-picture-upload').click();
    });

    document.getElementById('profile-picture-upload').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-picture-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);

            // Show SweetAlert confirmation
            Swal.fire({
                icon: 'success',
                title: 'Profile Picture Updated',
                text: 'Your profile picture has been successfully updated.',
            });
        }
    });

    document.getElementById('search-button').addEventListener('click', function() {
        const userId = document.getElementById('search-input').value;

        fetch(`{{ route('admin.medical-record.search') }}?user_id=${userId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const record = data.record;

                // Populate the form fields with the retrieved data
                document.getElementById('name').value = record.name;
                document.getElementById('birthdate').value = record.birthdate;
                document.getElementById('age').value = record.age;
                document.getElementById('address').value = record.address;
                document.getElementById('personal-contact-number').value = record.personal_contact_number;
                document.getElementById('emergency-contact-number').value = record.emergency_contact_number;
                document.getElementById('father-name').value = record.father_name;
                document.getElementById('mother-name').value = record.mother_name;
                document.getElementById('past-illness').value = record.past_illness;
                document.getElementById('chronic-conditions').value = record.chronic_conditions;
                document.getElementById('surgical-history').value = record.surgical_history;
                document.getElementById('family-medical-history').value = record.family_medical_history;
                document.getElementById('allergies').value = record.allergies;

                // Populate the checkbox group with the medicines
                const medicines = JSON.parse(record.medicines);
                const medicineCheckboxes = document.querySelectorAll('input[name="medicines[]"]');
                medicineCheckboxes.forEach(checkbox => {
                    checkbox.checked = medicines.includes(checkbox.value);
                });

                // Show success alert
                Swal.fire({
                    icon: 'success',
                    title: 'Record Found',
                    text: `Record for ${record.name} has been loaded.`,
                });
            } else {
                // Show error alert if no records were found
                Swal.fire({
                    icon: 'error',
                    title: 'No Records Found',
                    text: 'No medical records were found for the given user ID.',
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was an error processing your request. Please try again.',
            });
        });
    });

    // BMI Calculator function
    function calculateBMI() {
        const height = parseFloat(document.getElementById('height').value) / 100; // Convert cm to meters
        const weight = parseFloat(document.getElementById('weight').value);

        if (!isNaN(height) && !isNaN(weight) && height > 0) {
            const bmi = weight / (height * height);
            document.getElementById('bmi-value').textContent = bmi.toFixed(2);
        } else {
            document.getElementById('bmi-value').textContent = 'N/A';
        }
    }

    // Auto-calculate BMI when height and weight are input
    document.getElementById('height').addEventListener('input', calculateBMI);
    document.getElementById('weight').addEventListener('input', calculateBMI);

    function approveSignature() {
        Swal.fire({
            icon: 'success',
            title: 'Signature Approved',
            text: 'MD\'s signature has been approved.',
        });
    }
</script>
</x-app-layout>