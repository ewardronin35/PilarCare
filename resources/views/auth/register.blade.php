<x-guest-layout>
    <!-- Custom container to wrap the form and testimonial section -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
        }

        .container {
            background-color: #fff;
            width: 900px;
            height: 85%;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            overflow: hidden;
            animation: fadeIn 1s ease-in-out;
        }

        .form-container {
            width: 50%;
            padding: 40px;
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
    </style>

    <div class="container">
        <!-- Left Side (Form) -->
        <div class="form-container">
            <div class="center-signup">
                <h2>Sign up</h2>
            </div>
            <form method="POST" action="{{ route('register') }}" onsubmit="return validateForm()">
                @csrf
                <div class="input-wrapper">
                    <i class="fas fa-user-tag"></i>
                    <select id="role" name="role" class="input-field" required onchange="toggleRoleField()">
                        <option value="1">Parent</option>
                        <option value="2">Teacher</option>
                        <option value="3">Student</option>
                        <option value="4">Staff</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
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

                <!-- ID Field -->
                <div class="input-wrapper" id="idField" style="display:none;">
                    <i class="fas fa-id-card"></i>
                    <x-text-input id="id_number" class="input-field" type="text" name="id_number" :value="old('id_number')" required placeholder="ID Number"/>
                    <x-input-error :messages="$errors->get('id_number')" class="mt-2" />
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

                <div class="flex items-center mt-4">
                    <input type="checkbox" id="termsCheckbox" name="termsCheckbox">
                    <label for="termsCheckbox" class="ml-2">I agree with the <a onclick="showModal()" class="underline">Terms of Use & Privacy Policy</a></label>
                </div>
                <div class="error-message" id="error-message">You must agree to the terms and conditions before registering.</div>

                <div class="flex items-center justify-between mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                        {{ __('Already have an account?') }}
                    </a>

                    <button type="submit" class="submit-button">
                        {{ __('Sign up') }}
                    </button>
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
            <p>[Your terms of use and privacy policy content goes here...]</p>
        </div>
    </div>

    <script>
        function toggleRoleField() {
            const role = document.getElementById('role').value;
            const idField = document.getElementById('idField');
            idField.style.display = (role == 1 || role == 3 || role == 2 || role == 4) ? 'block' : 'none';
        }

        function showModal() {
            document.getElementById('termsModal').style.display = "block";
        }

        function hideModal() {
            document.getElementById('termsModal').style.display = "none";
        }

        // Close the modal if the user clicks outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('termsModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function validateForm() {
            const checkbox = document.getElementById('termsCheckbox');
            const errorMessage = document.getElementById('error-message');
            if (!checkbox.checked) {
                errorMessage.style.display = 'block';
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>
</x-guest-layout>
