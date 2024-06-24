<x-guest-layout>
    <!-- Custom container to wrap the form and testimonial section -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.nocaptcha.sitekey') }}"></script>
    <style>
        body {
            background: url('{{ asset('images/bg.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Arial', sans-serif;
            animation: fadeInBackground 1s ease-in-out;
            overflow: hidden;
        }

        .container {
            background-color: #fff;
            width: 900px;
            height: 80%;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            overflow: hidden;
            animation: fadeIn 1s ease-in-out;
        }

        .form-container {
            width: 60%;
            height: 90%;
            padding: 40px;
            overflow-y: auto; /* Allow vertical scrolling within form container */
        }

        .center-signup {
            text-align: center;
            color: #171A1F;
            font-size: 34px;
            margin-bottom: 20px;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 16px;
        }

        .input-wrapper i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: black;
            z-index: 2;
        }

        /* Specific classes for select icons */
        .input-wrapper i.icon-select-student-type,
        .input-wrapper i.icon-select-program,
        .input-wrapper i.icon-select-teacher-type {
            top: 60%; /* Adjusted to better align with select elements */
        }

        /* Additional padding for select elements with icons */
        .input-wrapper select.icon-select-student-type,
        .input-wrapper select.icon-select-program,
        .input-wrapper select.icon-select-teacher-type {
            padding-left: 35px;
        }

        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"],
        .form-container select {
            background-color: #F3F4F6;
            border: 1px solid #ccc;
            padding: 12px 20px 12px 35px;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            color: black;
            transition: all 0.3s ease-in-out;
        }

        .form-container .submit-button {
            margin-top: 20px;
            background-color: #1CE5FF;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container .submit-button:hover {
            background-color: #17B0CC;
        }

        .button-container {
            display: flex;
            justify-content: center;
        }

        .right-side {
            width: 50%;
            height: 100%;
            padding: 40px;
            background-color: #003FFF;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: slideIn 1s ease-in-out;
            overflow: hidden; /* Prevent scrolling on the logo side */
        }

        .logo {
            max-width: 100%;
            max-height: 100%;
        }

        .terms {
            margin-top: 20px;
            color: #141B34;
        }

        .terms a {
            color: #003FFF;
            text-decoration: underline;
            cursor: pointer;
        }

        .error-message {
            color: red;
            display: none;
            margin-top: 10px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
            }
            to {
                transform: translateX(0);
            }
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            animation: fadeIn 0.5s;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 10px;
            animation: slideIn 0.5s;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .login-link, .terms {
            text-align: center;
            margin-top: 10px;
        }

        .login-link a {
            color: #003FFF;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>

    <div class="container">
        <!-- Left Side (Form) -->
        <div class="form-container">
            <div class="center-signup">
                <h2>Sign up</h2>
            </div>
            <form method="POST" action="{{ route('register') }}" id="registration-form" onsubmit="return validateForm()">
                @csrf
                <div class="input-wrapper">
     <i class="fas fa-user-tag"></i>
    <select id="role" name="role" class="input-field" required onchange="toggleRoleField()">
        <option value="">Select Role</option>
        <option value="Parent">Parent</option>
        <option value="Teacher">Teacher</option>
        <option value="Student">Student</option>
        <option value="Staff">Staff</option>
    </select>
    <x-input-error :messages="$errors->get('role')" class="mt-2" />
    </div>
     <!-- ID Field -->
     <div class="input-wrapper" id="idField" style="display:none;">
                    <i class="fas fa-id-card"></i>
                    <x-text-input id="id_number" class="input-field" type="text" name="id_number" :value="old('id_number')" required placeholder="ID Number"/>
                    <x-input-error :messages="$errors->get('id_number')" class="mt-2" />
                </div>
                <!-- First Name -->
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <x-text-input id="first_name" class="input-field" type="text" name="first_name" :value="old('first_name')" required autocomplete="given-name" placeholder="First Name"/>
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>

                <!-- Last Name -->
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <x-text-input id="last_name" class="input-field" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" placeholder="Last Name"/>
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <x-text-input id="email" class="input-field" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="example.email@gmail.com"/>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Contact Number -->
                <div class="input-wrapper">
                    <i class="fas fa-phone"></i>
                    <x-text-input id="contact_number" class="input-field" type="text" name="contact_number" :value="old('contact_number')" required autocomplete="tel" placeholder="Contact Number"/>
                    <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                </div>

                <!-- Gender -->
                <div class="input-wrapper">
                    <i class="fas fa-venus-mars"></i>
                    <select id="gender" name="gender" class="input-field" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                </div>

               

                <!-- Student Fields -->
                <div id="student-fields" style="display:none;">
                    <div class="input-wrapper">
                        <i class="fas fa-graduation-cap icon-select-student-type"></i>
                        <label for="student_type">Student Type</label>
                        <select id="student_type" name="student_type" class="input-field icon-select-student-type" onchange="toggleStudentTypeFields()">
                            <option value="">Select Type</option>
                            <option value="TED">TED</option>
                            <option value="BED">BED</option>
                        </select>
                    </div>

                    <!-- TED Fields -->
                    <div id="ted-fields" style="display:none;">
                        <div class="input-wrapper">
                            <i class="fas fa-book icon-select-program"></i>
                            <label for="program">Program</label>
                            <select id="program" name="program" class="input-field icon-select-program">
                                <option value="BSN">BSN</option>
                                <option value="BSHM">BSHM</option>
                                <option value="BSTM">BSTM</option>
                                <option value="BSIT">BSIT</option>
                                <option value="BEED">BEED</option>
                                <option value="BLIS">BLIS</option>
                                <option value="BSBA">BSBA</option>
                            </select>
                        </div>
                        <div class="input-wrapper">
                            <i class="fas fa-calendar"></i>
                            <x-text-input id="year_level" class="input-field" type="text" name="year_level" :value="old('year_level')" placeholder="Year Level"/>
                        </div>
                        <div class="input-wrapper">
                            <i class="fas fa-users"></i>
                            <x-text-input id="year_section" class="input-field" type="text" name="year_section" :value="old('year_section')" placeholder="Year Section"/>
                        </div>
                    </div>

                    <!-- BED Fields -->
                    <div id="bed-fields" style="display:none;">
                        <div class="input-wrapper">
                            <label for="bed_type">BED Type</label>
                            <select id="bed_type" name="bed_type" class="input-field" onchange="toggleBedFields()">
                                <option value="">Select Type</option>
                                <option value="HS">HS</option>
                                <option value="SHS">SHS</option>
                                <option value="Elementary">Elementary</option>
                            </select>
                        </div>
                        <div class="input-wrapper">
                            <i class="fas fa-building"></i>
                            <x-text-input id="section" class="input-field" type="text" name="section" :value="old('section')" placeholder="Section"/>
                        </div>
                        <div class="input-wrapper">
                            <i class="fas fa-level-up-alt"></i>
                            <x-text-input id="grade" class="input-field" type="text" name="grade" :value="old('grade')" placeholder="Grade"/>
                        </div>
                    </div>
                </div>

                <!-- Teacher Fields -->
                <div id="teacher-fields" style="display:none;">
                    <div class="input-wrapper">
                        <i class="fas fa-chalkboard-teacher icon-select-teacher-type"></i>
                        <label for="teacher_type">Teacher Type</label>
                        <select id="teacher_type" name="teacher_type" class="input-field icon-select-teacher-type">
                            <option value="TED">TED</option>
                            <option value="BED">BED</option>
                        </select>
                    </div>
                </div>

                <!-- Staff Fields -->
                <div id="staff-fields" style="display:none;">
                    <div class="input-wrapper">
                        <label for="staff_role">Staff Role</label>
                        <x-text-input id="staff_role" class="input-field" type="text" name="staff_role" :value="old('staff_role')" placeholder="Staff Role"/>
                    </div>
                </div>

                <!-- Password -->
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <x-text-input id="password" class="input-field" type="password" name="password" required autocomplete="new-password" placeholder="Enter at least 8+ characters"/>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <x-text-input id="password_confirmation" class="input-field" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Re-enter Password"/>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- reCAPTCHA -->
                <div class="input-wrapper">
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                    <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2" />
                </div>

                <div class="flex items-center mt-4">
                    <input type="checkbox" id="termsCheckbox" name="termsCheckbox">
                    <label for="termsCheckbox" class="ml-2">I agree with the <a onclick="showModal()" class="underline">Terms of Use & Privacy Policy</a></label>
                </div>
                <div class="error-message" id="error-message">You must agree to the terms and conditions before registering.</div>

                <div class="button-container mt-4">
                    <button type="submit" class="submit-button">
                        {{ __('Sign up') }}
                    </button>
                </div>

                <div class="login-link">
                    <p>Don't have an account? <a class="underline" href="{{ route('login') }}">Sign In</a></p>
                </div>

                <div class="terms mt-4 text-center">
                    <p>By signing up, I agree with the <a onclick="showModal()" class="underline">Terms of Use & Privacy Policy</a></p>
                </div>
            </form>
        </div>

        <!-- Right Side (Logo) -->
        <div class="right-side">
            <img src="{{ asset('images/pilarLogo.png') }}" alt="Pilar College of Zamboanga City Logo" class="logo">
        </div>
    </div>

    <!-- The Modal -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="hideModal()">&times;</span>
            <h2>Terms of Use & Privacy Policy</h2>
            <div class="row">
                <p><strong>Effective Date:</strong> June 06, 4</p>
                <p><strong>1. Introduction</strong></p>
                <p>Welcome to Pilar College of Zamboanga City (the "College"). By accessing or using our services, you agree to comply with and be bound by these Terms of Use and Privacy Policy ("Terms"). If you do not agree with these Terms, please do not use our services.</p>
                
                <p><strong>2. User Accounts</strong></p>
                <p><strong>2.1 Registration:</strong> To use certain features of our services, you must create an account. You agree to provide accurate, current, and complete information during the registration process and to update such information to keep it accurate, current, and complete.</p>
                <p><strong>2.2 Account Security:</strong> You are responsible for safeguarding your password. You agree not to disclose your password to any third party and to take sole responsibility for any activities or actions under your account, whether or not you have authorized such activities or actions.</p>

                <p><strong>3. Use of Services</strong></p>
                <p><strong>3.1 Eligibility:</strong> You must be at least 18 years old or have the consent of a parent or guardian to use our services.</p>
                <p><strong>3.2 Prohibited Conduct:</strong> You agree not to use our services for any unlawful purpose or in any way that might harm, threaten, or endanger others.</p>

                <p><strong>4. Privacy Policy</strong></p>
                <p><strong>4.1 Information We Collect:</strong> We collect personal information that you provide to us, such as your name, email address, contact number, gender, role, ID number, and any medical information you choose to share.</p>
                <p><strong>4.2 How We Use Information:</strong> We use your personal information to provide, improve, and develop our services, to communicate with you, and to protect the College and our users.</p>
                <p><strong>4.3 Sharing Information:</strong> We do not share your personal information with third parties except as necessary to provide our services, comply with the law, or protect our rights.</p>
                <p><strong>4.4 Data Security:</strong> We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
                <p><strong>4.5 Your Rights:</strong> You have the right to access, update, or delete your personal information. You can do this through your account settings or by contacting us directly.</p>

                <p><strong>5. Medical Information</strong></p>
                <p><strong>5.1 Collection of Medical Information:</strong> We may collect medical information such as your medical history, dental records, and appointment details to provide personalized medical services.</p>
                <p><strong>5.2 Use of Medical Information:</strong> Your medical information is used strictly for providing healthcare services and is protected under applicable laws and regulations.</p>
                <p><strong>5.3 Confidentiality:</strong> We ensure that your medical information is kept confidential and is only accessible to authorized personnel who need it to provide medical services.</p>

                <p><strong>6. Intellectual Property</strong></p>
                <p>All content, trademarks, and data on this website, including but not limited to software, databases, text, graphics, icons, hyperlinks, private information, designs, and agreements, are the property of or licensed to the College and as such are protected from infringement by local and international legislation and treaties.</p>

                <p><strong>7. Limitation of Liability</strong></p>
                <p>The College will not be liable for any direct, indirect, incidental, special, or consequential damages arising out of or relating to the use or inability to use our services, even if we have been advised of the possibility of such damages.</p>

                <p><strong>8. Changes to Terms</strong></p>
                <p>We may update these Terms from time to time. If we make material changes, we will notify you by email or through a notice on our services prior to the change becoming effective. Your continued use of our services after the effective date of the updated Terms constitutes your acceptance of the changes.</p>

                <p><strong>9. Contact Us</strong></p>
                <p>If you have any questions about these Terms or our services, please contact us at:</p>
                <p>[Pilar College of Zamboanga City Contact Information]</p>

                <p><strong>10. Governing Law</strong></p>
                <p>These Terms are governed by and construed in accordance with the laws of [Your Jurisdiction], without regard to its conflict of law principles.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toggleRoleField();
        });

        function toggleRoleField() {
            const role = document.getElementById('role').value;
            const idField = document.getElementById('idField');
            const studentFields = document.getElementById('student-fields');
            const teacherFields = document.getElementById('teacher-fields');
            const staffFields = document.getElementById('staff-fields');

            studentFields.style.display = 'none';
            teacherFields.style.display = 'none';
            staffFields.style.display = 'none';

            clearFields(studentFields);
            clearFields(teacherFields);
            clearFields(staffFields);

            idField.style.display = 'block'; // Show ID field for all roles

            switch (role) {
                case 'Student':
                    studentFields.style.display = 'block';
                    break;
                case 'Teacher':
                    teacherFields.style.display = 'block';
                    break;
                case 'Staff':
                    staffFields.style.display = 'block';
                    break;
            }
        }

        function clearFields(container) {
            const inputs = container.querySelectorAll('input[type=text], select');
            inputs.forEach(input => input.value = '');
        }

        function toggleStudentTypeFields() {
            const studentType = document.getElementById('student_type').value;
            const tedFields = document.getElementById('ted-fields');
            const bedFields = document.getElementById('bed-fields');

            tedFields.style.display = 'none';
            bedFields.style.display = 'none';
            clearFields(tedFields);
            clearFields(bedFields);

            if (studentType === 'TED') {
                tedFields.style.display = 'block';
            } else if (studentType === 'BED') {
                bedFields.style.display = 'block';
            }
        }

        function showModal() {
            document.getElementById('termsModal').style.display = "block";
        }

        function hideModal() {
            document.getElementById('termsModal').style.display = "none";
        }

        window.onclick = function(event) {
            const modal = document.getElementById('termsModal');
            if (event.target == modal) {
                hideModal();
            }
        }

        function validateForm() {
            const checkbox = document.getElementById('termsCheckbox');
            const errorMessage = document.getElementById('error-message');
            if (!checkbox.checked) {
                errorMessage.style.display = 'block';
                return false;
            }
            return true;
        }

        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('services.nocaptcha.sitekey') }}', { action: 'submit' }).then(function(token) {
                document.getElementById('g-recaptcha-response').value = token;
            });
        });
    </script>
</x-guest-layout>