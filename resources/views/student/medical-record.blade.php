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
            margin-top: 30px;
        }

        .forms-container {
            display: flex;
            gap: 20px;
            width: 95%;
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
            display: none;
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

        .form-group input {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            color: #333;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-section {
            margin-top: 20px;
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

        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #f8f9fa;
        }

        .history-table th,
        .history-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .history-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .history-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .tab-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .tab-buttons button {
            font-family: 'Poppins', sans-serif;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-right: 10px;
          
            transition: background-color 0.3s, transform 0.3s;
        }

        .tab-buttons button.active {
            background-color: #0056b3;
            transform: scale(1.1);
        }

        .tab-content {
            display: none;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .tab-content.active {
            display: block;
        }
       /* Table Styling */
table {
    width: 90%;
    margin-left:100px;
    border-collapse: collapse;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: #ffffff;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 20px;
}

th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

th {
    background-color: #007bff;
    color: white;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 14px;
}

td {
    font-size: 14px;
    color: #333;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #eaf4ff;
    transition: background-color 0.3s ease;
}

/* Image Container Styling */
.image-container {
    width: 60px;
    height: 60px;
    overflow: hidden;
    border-radius: 5px;
    border: 2px solid #007bff;
    display: flex;
    justify-content: center;
    align-items: center;
}

.image-container img {
    width: 100%;
    height: auto;
}

.image-previews {
    display: flex;
    gap: 10px;
}

.image-previews .image-container {
    width: 80px;
    height: 80px;
    cursor: pointer; /* Make the images clickable for preview */
}

/* Heading */
h1 {
    font-size: 1.8rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

/* Modal Styling for Image Preview */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    justify-content: center;
    align-items: center;
}

.modal-content {
    position: relative;
    padding: 20px;
    background-color: white;
    border-radius: 10px;
    max-width: 80%;
    max-height: 80%;
}

.modal-content img {
    max-width: 100%;
    max-height: 100%;
}

.close {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 25px;
    color: #333;
    cursor: pointer;
}

/* Add some animation to the modal appearance */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
.btn {
    display: inline-block;
    padding: 12px 20px;
    font-family: 'Poppins', sans-serif;
    text-align: center;
    text-decoration: none;
    color: white;
    background-color: #007bff;
    border-radius: 8px;
    border: none;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 123, 255, 0.3);
}

.btn:hover {
    background-color: #0056b3;
    box-shadow: 0 6px 10px rgba(0, 123, 255, 0.4);
    transform: translateY(-2px);
}

.btn:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.4);
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}

    </style>

    <div class="main-content">
        
    <div class="tab-buttons">
    <button id="tab1" class="active" onclick="showTab('medical-record')">Medical Record</button>
    <button id="tab2" onclick="showTab('health-documents')">Health Documents</button>
        </div>
        <div id="medical-record" class="tab-content active">
        <div class="forms-container">
            <!-- Profile Information -->
            <div class="form-container">
                <div class="form-header">
                    <h2>Patient Information</h2>
                </div>
                <div class="profile-picture">
                    <img id="profile-picture-preview" src="{{ $information->profile_picture ? asset('storage/' . $information->profile_picture) : asset('images/pilarLogo.jpg') }}" alt="Profile Picture">
                    <button id="profile-picture-button" class="button">Choose Profile Picture</button>
                    <input type="file" id="profile-picture-upload" name="profile_picture" accept="image/*" class="hidden-input">
                </div>
                <form method="POST" action="{{ route('student.medical-record.store') }}" enctype="multipart/form-data" id="medical-record-form" onsubmit="return checkSubmit()">
                    @csrf
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ $name }}" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="birthdate">Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" value="{{ $information->birthdate ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" id="age" name="age" value="{{ $age }}" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" value="{{ $information->address ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="personal-contact-number">Personal Contact Number</label>
                            <input type="text" id="personal-contact-number" name="personal_contact_number" value="{{ $information->personal_contact_number ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="emergency-contact-number">Emergency Contact Number</label>
                            <input type="text" id="emergency-contact-number" name="emergency_contact_number" value="{{ $information->emergency_contact_number ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="father-name">Father's Name/Legal Guardian</label>
                            <input type="text" id="father-name" name="father_name" value="{{ $information->parent_name_father ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="mother-name">Mother's Name/Legal Guardian</label>
                            <input type="text" id="mother-name" name="mother_name" value="{{ $information->parent_name_mother ?? '' }}" required>
                        </div>
                    </div>
            </div>

            <!-- Medical Information -->
            <div class="form-container">
                <div class="form-header">
                    <h2>Medical Information</h2>
                </div>
              
                    <div class="form-section">
                        <h2>Medical History</h2>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="past-illness">Past Illnesses/Injuries</label>
                            <input type="text" id="past-illness" name="past_illness" value="{{ $information->medical_history ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="chronic-conditions">Chronic Conditions</label>
                            <input type="text" id="chronic-conditions" name="chronic_conditions" value="{{ $information->chronic_conditions ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group-inline">
                        <div class="form-group">
                            <label for="surgical-history">Surgical History</label>
                            <input type="text" id="surgical-history" name="surgical_history" value="{{ $information->surgical_history ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="family-medical-history">Family Medical History</label>
                            <input type="text" id="family-medical-history" name="family_medical_history" value="{{ $information->family_medical_history ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="allergies">Allergies</label>
                        <input type="text" id="allergies" name="allergies" value="{{ $information->allergies ?? '' }}" required>
                    </div>
                    <div class="form-section">
                        <h2>Medicines OK to give/apply at the clinic</h2>
                        <div class="form-group">
                            <select id="medicines" name="medicines[]" multiple class="form-control" required>
                                <option value="Paracetamol" @if(in_array('Paracetamol', explode(',', $information->medicines ?? ''))) selected @endif>Paracetamol</option>
                                <option value="Ibuprofen" @if(in_array('Ibuprofen', explode(',', $information->medicines ?? ''))) selected @endif>Ibuprofen</option>
                                <option value="Mefenamic Acid" @if(in_array('Mefenamic Acid', explode(',', $information->medicines ?? ''))) selected @endif>Mefenamic Acid</option>
                                <option value="Citirizine/Loratadine" @if(in_array('Citirizine/Loratadine', explode(',', $information->medicines ?? ''))) selected @endif>Citirizine/Loratadine</option>
                                <option value="Camphor + Menthol Liniment" @if(in_array('Camphor + Menthol Liniment', explode(',', $information->medicines ?? ''))) selected @endif>Camphor + Menthol Liniment</option>
                                <option value="PPA" @if(in_array('PPA', explode(',', $information->medicines ?? ''))) selected @endif>PPA</option>
                                <option value="Phenylephrine" @if(in_array('Phenylephrine', explode(',', $information->medicines ?? ''))) selected @endif>Phenylephrine</option>
                                <option value="Antacid" @if(in_array('Antacid', explode(',', $information->medicines ?? ''))) selected @endif>Antacid</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="button">Save</button>
                    </div>
                </form>
            </div>

            <!-- Medical Record History -->
            <div class="form-container">
                <div class="form-header">
                
                    <h2>Medical Record History</h2>
                    </div>
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
                        @foreach($medicalRecords as $record)
                            <tr>
                                <td>{{ $record->name }}</td>
                                <td>{{ $record->age }}</td>
                                <td>{{ $record->personal_contact_number }}</td>
                                <td>{{ $record->past_illness }}</td>
                                <td>{{ $record->surgical_history }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h2>Physical Examination History</h2>
<table class="history-table">
    <thead>
        <tr>
            <th>Height</th>
            <th>Weight</th>
            <th>Vision</th>
            <th>Medicine Intake</th>
            <th>Remarks</th>
            <th>MD Approved</th>
        </tr>
    </thead>
    <tbody id="physical-examination-history-body">
        @foreach($physicalExaminations as $examination)
            <tr>
                <td>{{ $examination->height }}</td>
                <td>{{ $examination->weight }}</td>
                <td>{{ $examination->vision }}</td>
                <td>{{ $examination->medicine_intake }}</td>
                <td>{{ $examination->remarks }}</td>
                <td>{{ $examination->md_approved ? 'Yes' : 'No' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

            </div>
        </div>
    </div>
    </div>
    <div id="health-documents" class="tab-content">
    <h1>Health Documents</h1>
    <a href="{{ route('student.medical-record.downloadPdf', $medicalRecord->id) }}" class="btn btn-primary">
            Download Medical Record PDF
        </a>
        <a href="{{ route('student.health-examination.downloadPdf', $healthExamination->id) }}" class="btn btn-primary"> Download PDF </a>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Health Exam Picture</th>
                    <th>X-ray Pictures</th>
                    <th>Lab Result Pictures</th>
                </tr>
            </thead>
            <tbody>
                @if($healthExaminationPictures->isEmpty())
                    <tr>
                        <td colspan="3">No health examination pictures available.</td>
                    </tr>
                @else
                    @foreach($healthExaminationPictures as $examination)
                        <tr>
                            <td>
                                @if($examination->health_examination_picture)
                                    <div class="image-container">
                                        <img src="{{ asset('storage/' . $examination->health_examination_picture) }}" alt="Health Examination Picture">
                                    </div>
                                @else
                                    <span>No Health Exam Picture</span>
                                @endif
                            </td>
                            <td>
                                <div class="image-previews">
                                    @if($examination->xray_picture)
                                        @foreach(json_decode($examination->xray_picture) as $xray)
                                            <div class="image-container">
                                                <img src="{{ asset('storage/' . $xray) }}" alt="X-ray Picture">
                                            </div>
                                        @endforeach
                                    @else
                                        <span>No X-ray Pictures</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="image-previews">
                                    @if($examination->lab_result_picture)
                                        @foreach(json_decode($examination->lab_result_picture) as $lab)
                                            <div class="image-container">
                                                <img src="{{ asset('storage/' . $lab) }}" alt="Lab Result Picture">
                                            </div>
                                        @endforeach
                                    @else
                                        <span>No Lab Result Pictures</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>


<div id="imageModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <img id="modalImage" src="" alt="Image Preview">
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function checkSubmit() {
    // Example validation: ensure that a field is filled
    const name = document.getElementById('name').value;
    if (!name) {
        alert('Please fill out the name field');
        return false; // Prevent form submission
    }

    // If everything is fine, return true to allow form submission
    return true;
}
function showTab(tabId) {
    // Get all tab contents and tab buttons
    const tabs = document.querySelectorAll('.tab-content');
    const buttons = document.querySelectorAll('.tab-buttons button');

    // Hide all tab contents
    tabs.forEach(tab => tab.classList.remove('active'));
    
    // Remove active class from all buttons
    buttons.forEach(button => button.classList.remove('active'));

    // Show the selected tab content and activate the corresponding button
    document.getElementById(tabId).classList.add('active');
    document.querySelector(`button[onclick="showTab('${tabId}')"]`).classList.add('active');
}

document.querySelectorAll('.image-container img').forEach(image => {
    image.addEventListener('click', function() {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modal.style.display = "flex";
        modalImage.src = this.src;
    });
});

// Close the modal when clicking the close button
function closeModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = "none";
}

// Close the modal when clicking outside the modal content
window.onclick = function(event) {
    const modal = document.getElementById('imageModal');
    if (event.target === modal) {
        modal.style.display = "none";
    }
};
        document.getElementById('profile-picture-button').addEventListener('click', function () {
            document.getElementById('profile-picture-upload').click();
        });

        document.getElementById('profile-picture-upload').addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profile-picture-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);

                // SweetAlert confirmation
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Picture Updated',
                    text: 'Your profile picture has been successfully updated.',
                });
            }
        });

        // Automatically calculate age based on birthdate
        document.getElementById('birthdate').addEventListener('change', function () {
            const birthdate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthdate.getFullYear();
            const m = today.getMonth() - birthdate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }
            document.getElementById('age').value = age;
        });

        // Form Submission
        $('#medical-record-form').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('student.medical-record.store') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Record Saved',
                        text: 'The medical record has been saved successfully.',
                    });

                    // Append the new record to the table
                    let newRow = `
                        <tr>
                            <td>${response.name}</td>
                            <td>${response.age}</td>
                            <td>${response.personal_contact_number}</td>
                            <td>${response.past_illness}</td>
                            <td>${response.surgical_history}</td>
                        </tr>
                    `;
                    $('#medical-record-history-body').append(newRow);

                    // Optionally clear the form after successful submission
                    $('#medical-record-form')[0].reset();
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was a problem saving the record.',
                    });
                }
            });
        });
    </script>
</x-app-layout>
