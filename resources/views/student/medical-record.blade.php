<div class="form-container">
    <div class="profile-picture">
        <img id="profile-picture-preview" src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture">
        <label for="profile-picture-upload" class="button">Choose Profile Picture</label>
        <input type="file" id="profile-picture-upload" name="profile_picture" accept="image/*">
    </div>
    <form method="POST" action="{{ route('student.medical-record.store') }}" enctype="multipart/form-data" id="medical-record-form">
        @csrf
        <div class="form-group-inline">
            <div class="form-group">
                <label for="name">Name <i class="fa-regular fa-user"></i></label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="birthdate">Birthdate <i class="fa-regular fa-calendar-alt"></i></label>
                <input type="date" id="birthdate" name="birthdate" required>
            </div>
        </div>
        <div class="form-group-inline">
            <div class="form-group">
                <label for="age">Age <i class="fa-regular fa-hourglass-half"></i></label>
                <input type="number" id="age" name="age" required>
            </div>
            <div class="form-group">
                <label for="address">Address <i class="fa-regular fa-map-marker-alt"></i></label>
                <input type="text" id="address" name="address" required>
            </div>
        </div>
        <div class="form-group-inline">
            <div class="form-group">
                <label for="personal-contact-number">Personal Contact Number <i class="fa-regular fa-phone"></i></label>
                <input type="text" id="personal-contact-number" name="personal_contact_number" required>
            </div>
            <div class="form-group">
                <label for="emergency-contact-number">Emergency Contact Number <i class="fa-regular fa-phone-alt"></i></label>
                <input type="text" id="emergency-contact-number" name="emergency_contact_number" required>
            </div>
        </div>
        <div class="form-group-inline">
            <div class="form-group">
                <label for="father-name">Father's Name/Legal Guardian <i class="fa-regular fa-user"></i></label>
                <input type="text" id="father-name" name="father_name" required>
            </div>
            <div class="form-group">
                <label for="mother-name">Mother's Name/Legal Guardian <i class="fa-regular fa-user"></i></label>
                <input type="text" id="mother-name" name="mother_name" required>
            </div>
        </div>
        <div class="form-group-inline">
            <div class="form-group">
                <label for="past-illness">Past Illness and Injuries <i class="fa-regular fa-notes-medical"></i></label>
                <input type="text" id="past-illness" name="past_illness" required>
            </div>
            <div class="form-group">
                <label for="chronic-conditions">Chronic Conditions <i class="fa-regular fa-heartbeat"></i></label>
                <input type="text" id="chronic-conditions" name="chronic_conditions" required>
            </div>
        </div>
        <div class="form-group-inline">
            <div class="form-group">
                <label for="surgical-history">Surgical History <i class="fa-regular fa-scalpel"></i></label>
                <input type="text" id="surgical-history" name="surgical_history" required>
            </div>
            <div class="form-group">
                <label for="family-medical-history">Family Medical History <i class="fa-regular fa-history"></i></label>
                <input type="text" id="family-medical-history" name="family_medical_history" required>
            </div>
        </div>
        <div class="form-group">
            <label for="allergies">Allergies (specify) <i class="fa-regular fa-allergies"></i></label>
            <input type="text" id="allergies" name="allergies" required>
        </div>
        <div class="form-section">
            <h2>Medicines OK to give/apply at the clinic (check)</h2>
            <div class="checkbox-group">
                <label><input type="checkbox" name="medicines[]" value="Paracetamol"> Paracetamol</label>
                <label><input type="checkbox" name="medicines[]" value="Ibuprofen"> Ibuprofen</label>
                <label><input type="checkbox" name="medicines[]" value="Mefenamic Acid"> Mefenamic Acid</label>
                <label><input type="checkbox" name="medicines[]" value="Citirizine/Loratadine"> Citirizine/Loratadine</label>
                <label><input type="checkbox" name="medicines[]" value="Camphor + Menthol Liniment"> Camphor + Menthol Liniment</label>
                <label><input type="checkbox" name="medicines[]" value="PPA"> PPA</label>
                <label><input type="checkbox" name="medicines[]" value="Phenylephrine"> Phenylephrine</label>
                <label><input type="checkbox" name="medicines[]" value="Antacid"> Antacid</label>
            </div>
        </div>
        <div class="form-group">
            <button type="button" class="button" onclick="temporarySave('medical')">Save </button>
        </div>
    </form>
</div>
