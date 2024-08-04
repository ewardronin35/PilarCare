<div class="form-container">
    <div class="dental-record-container">
        <div class="teeth-chart">
            <img src="https://i0.wp.com/coreem.net/content/uploads/2019/10/Classification-of-Teeth.png?fit=676%2C722&ssl=1&fbclid=IwZXh0bgNhZW0CMTAAAR3sEwpCmV8_yU6M4DnI6wbG9pF6GKd2RXVnJ0nM90ukOQFrCFa15J2-0do_aem_mxPOrrPZUhH5Iw7RCWUNog" alt="Teeth Chart" style="width:100%;">
        </div>
        <form method="POST" action="{{ route('student.dental-record.store') }}" enctype="multipart/form-data" class="dental-form">
            @csrf
            <div class="form-group-inline">
                <div class="form-group">
                    <label for="name">Name <i class="fa-regular fa-user"></i></label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="year-section">Year and Section <i class="fa-regular fa-calendar-alt"></i></label>
                    <input type="text" id="year-section" name="year_section" required>
                </div>
            </div>
            <div class="form-group">
                <label for="date">Date <i class="fa-regular fa-calendar-alt"></i></label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="diagnosis">Diagnosis <i class="fa-regular fa-notes-medical"></i></label>
                <input type="text" id="diagnosis" name="diagnosis" required>
            </div>
            <div class="form-group">
                <label for="recommendation">Recommendation <i class="fa-regular fa-receipt"></i></label>
                <input type="text" id="recommendation" name="recommendation" required>
            </div>
            <div class="form-group">
                <label for="prescription">Doctor's/Dentist's Prescription <i class="fa-regular fa-prescription"></i></label>
                <textarea id="prescription" name="prescription" required></textarea>
            </div>
            <div class="form-group">
                <label for="nurse-note">Nurse's Note <i class="fa-regular fa-notes-medical"></i></label>
                <textarea id="nurse-note" name="nurse_note" required></textarea>
            </div>
            <div class="form-group-inline">
                <div class="form-group">
                    <label for="upper-molars">Upper Molar Right First <i class="fa fa-tooth"></i></label>
                    <select id="upper-molars" name="upper_molars[]" required>
                        <option value="healthy">Healthy</option>
                        <option value="cavity">Cavity</option>
                        <option value="missing">Missing</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="upper-molars-2">Upper Molar Right Second <i class="fa fa-tooth"></i></label>
                    <select id="upper-molars-2" name="upper_molars[]" required>
                        <option value="healthy">Healthy</option>
                        <option value="cavity">Cavity</option>
                        <option value="missing">Missing</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="upper-molars-3">Upper Molar Right Third <i class="fa fa-tooth"></i></label>
                    <select id="upper-molars-3" name="upper_molars[]" required>
                        <option value="healthy">Healthy</option>
                        <option value="cavity">Cavity</option>
                        <option value="missing">Missing</option>
                    </select>
                </div>
            </div>
            <div class="form-group-inline">
                <div class="form-group">
                    <label for="upper-premolars">Upper Premolar Right First <i class="fa fa-tooth"></i></label>
                    <select id="upper-premolars" name="upper_premolars[]" required>
                        <option value="healthy">Healthy</option>
                        <option value="cavity">Cavity</option>
                        <option value="missing">Missing</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="upper-premolars-2">Upper Premolar Right Second <i class="fa fa-tooth"></i></label>
                    <select id="upper-premolars-2" name="upper_premolars[]" required>
                        <option value="healthy">Healthy</option>
                        <option value="cavity">Cavity</option>
                        <option value="missing">Missing</option>
                    </select>
                </div>
            </div>
            <div class="form-group-inline">
                <div class="form-group">
                    <label for="upper-canine">Upper Canine Right <i class="fa fa-tooth"></i></label>
                    <select id="upper-canine" name="upper_canine[]" required>
                        <option value="healthy">Healthy</option>
                        <option value="cavity">Cavity</option>
                        <option value="missing">Missing</option>
                    </select>
                </div>
            </div>
            <div class="form-group-inline">
                <div class="form-group">
                    <label for="upper-incisors">Upper Incisor Right First <i class="fa fa-tooth"></i></label>
                    <select id="upper-incisors" name="upper_incisors[]" required>
                        <option value="healthy">Healthy</option>
                        <option value="cavity">Cavity</option>
                        <option value="missing">Missing</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="upper-incisors-2">Upper Incisor Right Second <i class="fa fa-tooth"></i></label>
                    <select id="upper-incisors-2" name="upper_incisors[]" required>
                        <option value="healthy">Healthy</option>
                        <option value="cavity">Cavity</option>
                        <option value="missing">Missing</option>
                    </select>
                </div>
            </div>
            <div class="form-group-inline">
                <div class="form-group">
                    <label for="lower-molars">Lower Molar Right First <i class="fa fa-tooth"></i></label>
                    <select id="lower-molars" name="lower_molars[]" required>
                        <option value="healthy">Healthy</option>
                        <option value="cavity">Cavity</option>
                        <option value="missing">Missing</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="lower-molars-2">Lower Molar Right Second <i class="fa fa-tooth"></i></label>
                    <select id="lower-molars-2" name="lower_molars[]" required>
                        <option value="healthy">Healthy</option>
                        <option value="cavity">Cavity</option>
                        <option value="missing">Missing</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="lower-molars-3">Lower Molar Right Third <i class="fa fa-tooth"></i></label>
                    <select id="lower-molars-3" name="lower_molars[]" required>
                        <option value="healthy">Healthy</option>
                        <option value="cavity">Cavity</option>
                        <option value="missing">Missing</option>
                    </select>
                </div>
            </div>
            <div class="form-group-inline">
                <div class="form-group">
                    <label for="lower-premolars">Lower Premolar Right First <i class="fa fa-tooth"></i></label>
                    <select id="lower-premolars" name="lower_premolars[]" required>
                        <option value="healthy">Healthy</option>
                        <option value="cavity">Cavity</option>
                        <option value="missing">Missing</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="lower-premolars-2">Lower Premolar Right Second <i class="fa fa-tooth"></i></label>
                    <select id="lower-premolars-2" name="lower_premolars[]" required>
                        <option value="healthy">Healthy</option>
                        <option value="cavity">Cavity</option>
                        <option value="missing">Missing</option>
                    </select>
                </div>
            </div>
            <div class="form-group-inline">
                <div class="form-group">
                    <label for="lower-canine">Lower Canine Right <i class="fa fa-tooth"></i></label>
                    <select id="lower-canine" name="lower_canine[]" required>
                        <option value="healthy">Healthy</option>
                        <option value="cavity">Cavity</option>
                        <option value="missing">Missing</option>
                    </select>
                </div>
            </div>
            <div class="form-group-inline">
                <div class="form-group">
                    <label for="lower-incisors">Lower Incisor Right First <i class="fa fa-tooth"></i></label>
                    <select id="lower-incisors" name="lower_incisors[]" required>
                        <option value="healthy">Healthy</option>
                        <option value="cavity">Cavity</option>
                        <option value="missing">Missing</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="lower-incisors-2">Lower Incisor Right Second <i class="fa fa-tooth"></i></label>
                    <select id="lower-incisors-2" name="lower_incisors[]" required>
                        <option value="healthy">Healthy</option>
                        <option value="cavity">Cavity</option>
                        <option value="missing">Missing</option>
                    </select>
                </div>
            </div>
            <!-- Repeat the above pattern for all 32 teeth -->
            <div class="form-group">
                <button type="submit" class="button">Submit</button>
                <button type="button" class="button" onclick="temporarySave('dental')">Save Temporarily</button>
            </div>
        </form>
    </div>
</div>
