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
            margin: 0 auto;
            grid-template-columns: 1fr 1fr; /* Two columns layout */

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
    width: 100%; /* Ensure full width */
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
            transition: opacity 0.5s ease-in-out; /* Transition effect */

        }

        .tab-content.active {
            display: block;
            opacity: 1;

        }
       /* Table Styling */
       .table-container {
    background-color: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
    border: 1px solid #eaeaea;
    margin-top: 20px;
    margin-bottom: 20px;
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
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8); /* Black background with opacity */
    justify-content: center;
    align-items: center;
    transition: opacity 0.3s ease-in-out;
}
.modal.active {
    display: flex;
    opacity: 1;
}

.tab-content {
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

.tab-content.active {
    opacity: 1;
}

.modal-content {
    position: relative;
    width: 80%;
    max-width: 600px; /* Fixed size */
    height: 80%;
    max-height: 600px; /* Fixed size */
    background-color: white;
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    animation: zoomIn 0.4s ease-in-out; /* Animation */
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
@keyframes zoomIn {
    from {
        transform: scale(0.5);
    }
    to {
        transform: scale(1);
    }
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
@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
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
@media (max-width: 768px) {
    .history-table th, .history-table td {
        font-size: 0.85rem; /* Reduce font size for smaller screens */
        padding: 10px;
    }
}
    </style>

    <div class="main-content">
        
    <div class="tab-buttons">
    <button id="back-to-medical-records" class="btn btn-primary" onclick="window.location.href='{{ route('student.medical-record') }}';">Medical Record</button>
    <button id="back-to-medical-records" class="btn btn-primary" onclick="window.location.href='{{ route('student.medical-record') }}';">Health Documents</button>
    <button id="tab3" class="active" onclick="showTab('physical-exam')">Update Physical Examination</button>

        </div>
    
<div id="physical-exam" class="tab-content">
    <div class="forms-container" style="display: flex; justify-content: space-between; gap: 20px;">
        
        <!-- Update Physical Examination Form on the Left -->
        <div class="form-container" style="flex: 1;">
            <div class="form-header">
                <h2>Update Physical Examination</h2>
            </div>

            <form id="physical-exam-form" method="POST" action="{{ route('student.physical-exam.update', $physicalExam->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
                <div class="form-group-inline">
                    <div class="form-group">
                        <label for="height">Height (cm)</label>
                        <input type="number" id="height" name="height" min="1" value="{{ old('height') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="weight">Weight (kg)</label>
                        <input type="number" id="weight" name="weight" min="1" value="{{ old('weight') }}" required>
                    </div>
                </div>

                <div class="form-group-inline">
                    <div class="form-group">
                        <label for="vision">Vision (20/20)</label>
                        <input type="text" id="vision" name="vision" value="{{ old('vision') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea id="remarks" name="remarks">{{ old('remarks') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
    <label for="physical_exam_image">Upload Physical Examination Picture</label>
    <input type="file" id="physical_exam_image" name="physical_exam_image[]" accept="image/*" multiple onchange="previewPhysicalExamImages(event)">
</div>
<div id="physical-exam-previews" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>


        <!-- Image Preview Modal -->
       
                <div class="form-group">
                    <button type="submit" class="button btn-primary">Save</button>
                </div>
            </form>
        </div>     

        <!-- Update Medical Record Form on the Right -->
        <div class="form-container" style="flex: 1;">
    <div class="form-header">
        <h2>Medical Information</h2>
    </div>

    <form id="medical-record-form" method="POST" action="{{ route('student.medical-record.store') }}">
        @csrf
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
        <div class="form-group">
    <label for="medical_info_images">Upload Medical Information Pictures</label>
    <input type="file" id="medical_info_images" name="medical_info_images[]" accept="image/*" multiple onchange="previewMedicalInfoImages(event)">
</div>
<div id="medical-info-previews" style="display: flex; gap: 10px;"></div>

        <div class="form-group">
            <button type="submit" class="button btn-primary">Save</button>
        </div>
    </form>
</div>


    <!-- Medicine Intake Section -->
    <div class="form-container" style="margin-top: 20px; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: white;">
    <div class="form-header" style="text-align: center; margin-bottom: 20px;">
        <h2>Medicine Intake</h2>
    </div>

    <form id="medicine-intake-form" method="POST" action="">
        @csrf
        <div class="form-group-inline" style="display: flex; justify-content: space-between; gap: 20px;">
            <div class="form-group" style="flex: 1;">
                <label for="medicine_name">Medicine Name</label>
                <input type="text" id="medicine_name" name="medicine_name" value="{{ old('medicine_name') }}" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="dosage">Dosage</label>
                <input type="text" id="dosage" name="dosage" value="{{ old('dosage') }}" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
            </div>
        </div>

        <div class="form-group-inline" style="display: flex; justify-content: space-between; gap: 20px; margin-top: 20px;">
            <div class="form-group" style="flex: 1;">
                <label for="intake_time">Time of Intake</label>
                <input type="time" id="intake_time" name="intake_time" value="{{ old('intake_time') }}" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="form-group" style="margin-top: 20px; text-align: center;">
            <button type="submit" class="button btn-primary" style="padding: 12px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Save</button>
        </div>
    </form>
</div>

<div id="physicalImageModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closePhysicalModal()">&times;</span>
        <img id="physicalModalImage" src="" alt="Physical Exam Image">
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const id_number = "{{ $user->id_number }}"; // Ensure user ID is available for loading data

    // Initially attach listeners for images
    attachImageListeners();

    // Function to attach listeners for health and physical exam images
    function attachImageListeners() {
        const healthImages = document.querySelectorAll('#health-documents .image-container img');
        const physicalImages = document.querySelectorAll('#physical-exam .image-container img');

        console.log("Attaching listeners to images...");

        healthImages.forEach(image => {
            image.removeEventListener('click', handleHealthImageClick);
            image.addEventListener('click', handleHealthImageClick);
        });

        physicalImages.forEach(image => {
            image.removeEventListener('click', handlePhysicalImageClick);
            image.addEventListener('click', handlePhysicalImageClick);
        });
    }

  


    // Function to open the health document modal
    function openHealthModal(imageSrc) {
        const modal = document.getElementById('healthImageModal');
        const modalImage = document.getElementById('healthModalImage');
        if (imageSrc) {
            modalImage.src = imageSrc;
            modal.style.display = 'flex';
            console.log("Opened health modal with image:", imageSrc);
        }
    }

    // Function to open the physical exam modal
    function openPhysicalModal(imageSrc) {
        const modal = document.getElementById('physicalImageModal');
        const modalImage = document.getElementById('physicalModalImage');
        if (imageSrc) {
            modalImage.src = imageSrc;
            modal.style.display = 'flex';
            console.log("Opened physical modal with image:", imageSrc);
        }
    }

    // Close modals when clicking outside
    window.onclick = function (event) {
        const healthModal = document.getElementById('healthImageModal');
        const physicalModal = document.getElementById('physicalImageModal');
        if (event.target === healthModal) {
            healthModal.style.display = 'none';
            console.log("Closed health modal");
        } else if (event.target === physicalModal) {
            physicalModal.style.display = 'none';
            console.log("Closed physical modal");
        }
    };

    // Show tab function for switching between tabs
    window.showTab = function (tabId) {
        const tabs = document.querySelectorAll('.tab-content');
        const buttons = document.querySelectorAll('.tab-buttons button');

        // Hide all tabs
        tabs.forEach(tab => {
            tab.classList.remove('active');
            tab.style.display = 'none';
        });

        // Remove active class from buttons
        buttons.forEach(button => button.classList.remove('active'));

        // Show the selected tab
        const activeTab = document.getElementById(tabId);
        activeTab.classList.add('active');
        activeTab.style.display = 'block';

        // Reattach image listeners only for health-documents and physical-exam tabs
        if (tabId === 'health-documents' || tabId === 'physical-exam') {
            console.log("Reattaching image listeners for tab:", tabId);
            attachImageListeners();
        }

        // Mark the selected tab button as active
        document.querySelector(`button[onclick="showTab('${tabId}')"]`).classList.add('active');
    };

    // Automatically open the tab based on the URL hash (e.g., #physical-exam)
    function showTabBasedOnHash() {
        const hash = window.location.hash;
        if (hash) {
            const tabId = hash.substring(1); // Remove the '#' character
            showTab(tabId); // Call the showTab function with the tab ID
        }
    }

    // Call the function to show the tab based on the hash
    showTabBasedOnHash();

    // Optional: If the hash changes, update the tab
    window.addEventListener('hashchange', showTabBasedOnHash);

    // Make modal close functions globally accessible
    window.closeHealthModal = function () {
        document.getElementById('healthImageModal').style.display = 'none';
        console.log("Closed health modal");
    };

    window.closePhysicalModal = function () {
        document.getElementById('physicalImageModal').style.display = 'none';
        console.log("Closed physical modal");
    };

});

// Function to preview Physical Examination images
function previewPhysicalExamsImages(event) {
    const previewsContainer = document.getElementById('physical-exam-previews');
    previewsContainer.innerHTML = ''; // Clear existing previews

    const files = event.target.files;
    if (files) {
        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const imgElement = document.createElement('img');
                imgElement.src = e.target.result;
                imgElement.style.maxWidth = '100px';
                imgElement.style.marginRight = '10px';

                const labelElement = document.createElement('p');
                labelElement.textContent = `Physical Examination Picture ${index + 1}`;
                labelElement.style.textAlign = 'center';

                const previewContainer = document.createElement('div');
                previewContainer.style.textAlign = 'center';
                previewContainer.appendChild(imgElement);
                previewContainer.appendChild(labelElement);

                previewsContainer.appendChild(previewContainer);
            };
            reader.readAsDataURL(file);
        });
    }
}

// Function to preview Medical Information images

</script>
</x-app-layout>