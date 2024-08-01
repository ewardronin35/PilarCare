<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.nocaptcha.sitekey') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            width: 800px;
            height: 65%;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            overflow: hidden;
            animation: fadeIn 1s ease-in-out;
        }

        .form-container {
            width: 60%;
            height: 100%;
            padding: 40px;
            overflow-y: auto;
            min-height: 600px;
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
            width: 40%;
            height: 100%;
            padding: 40px;
            background-color: #003FFF;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            animation: slideIn 1s ease-in-out;
            overflow: hidden;
        }

        .logo {
            max-width: 100%;
            max-height: 100%;
        }

        .logo-text {
            color: white;
            font-size: 24px;
            margin-top: 20px;
            text-align: center;
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
            visibility: hidden;
            font-size: 14px;
            max-width: 100%;
            position: absolute;
        }

        .error-message.visible {
            visibility: visible;
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

        .modal {
            display: none;
            position: fixed;
            z-index: 10;
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

        .hide-icons .fas, .hide-icons .fa {
            display: none !important;
        }

        .toast-top-right {
            right: 12px;
            top: 12px;
        }

        .name-container,
        .id-role-container {
            display: flex;
            justify-content: space-between;
        }

        .name-container .input-wrapper,
        .id-role-container .input-wrapper {
            width: 48%;
        }

        .terms-container {
            margin-top: 20px;
            display: flex;
            align-items: center;
        }

        .terms-container input {
            margin-right: 10px;
        }

        .spinner {
            display: none;
            position: fixed;
            z-index: 9999;
            height: 2em;
            width: 2em;
            overflow: show;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        .spinner:before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
        }

        .spinner:not(:required):after {
            content: '';
            display: block;
            font-size: 10px;
            width: 1em;
            height: 1em;
            margin-top: -0.5em;
            animation: spinner 150ms infinite linear;
            border-radius: 0.5em;
            box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
        }

        @keyframes spinner {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <div class="container">
        <div class="form-container">
            <div class="center-signup">
                <h2>Sign up</h2>
            </div>
            <form method="POST" action="{{ route('register') }}" id="registration-form" enctype="multipart/form-data">
                @csrf
                <div class="id-role-container">
                    <div class="input-wrapper">
                        <i class="fas fa-id-card"></i>
                        <x-text-input id="id_number" class="input-field" type="text" name="id_number" :value="old('id_number')" maxlength="7" placeholder="ID Number" pattern="[A-Za-z]{1}[0-9]{6}" title="ID number must start with a letter followed by 6 numbers" required />
                        <x-input-error :messages="$errors->get('id_number')" class="input-error" />
                    </div>
                    <div class="input-wrapper">
                        <i class="fas fa-user-tag"></i>
                        <select id="role" class="input-field" name="role" required>
                            <option value="" disabled selected>Select Role</option>
                            <option value="Student">Student</option>
                            <option value="Teacher">Teacher</option>
                            <option value="Staff">Staff</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="input-error" />
                    </div>
                </div>
                <div class="name-container">
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <x-text-input id="first_name" class="input-field" type="text" name="first_name" :value="old('first_name')" placeholder="First Name" required />
                        <x-input-error :messages="$errors->get('first_name')" class="input-error" />
                    </div>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <x-text-input id="last_name" class="input-field" type="text" name="last_name" :value="old('last_name')" placeholder="Last Name" required />
                        <x-input-error :messages="$errors->get('last_name')" class="input-error" />
                    </div>
                </div>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <x-text-input id="email" class="input-field" type="email" name="email" :value="old('email')" placeholder="Email" required />
                    <x-input-error :messages="$errors->get('email')" class="input-error" />
                </div>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <x-text-input id="password" class="input-field" type="password" name="password" placeholder="Password" required />
                    <x-input-error :messages="$errors->get('password')" class="input-error" />
                </div>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <x-text-input id="password_confirmation" class="input-field" type="password" name="password_confirmation" placeholder="Confirm Password" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="input-error" />
                </div>
                <div class="terms-container">
                    <input type="checkbox" id="agreeCheckbox" required>
                    <label for="agreeCheckbox">I agree to the <a href="#" onclick="openTermsModal()" style="color: #003FFF;">terms and conditions</a></label>
                </div>
                <div class="button-container mt-4">
                    <button type="submit" class="submit-button">
                        {{ __('Sign up') }}
                    </button>
                </div>
                <div class="login-link">
                    <p>Already have an account? <a class="underline" href="{{ route('login') }}">Sign In</a></p>
                </div>
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
            </form>
        </div>
        <div class="right-side">
            <img src="{{ asset('images/pilarLogo.png') }}" alt="Pilar College of Zamboanga City Logo" class="logo">
            <div class="logo-text">PilarCare</div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div class="spinner" id="spinner"></div>

    <!-- Terms Modal -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeTermsModal()">&times;</span>
            <h2>Personal Data Notice</h2>
            <p>You are informed that by registering, you will need to provide personal data. This data is collected in accordance with the Data Privacy Act of 2012.</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('registration-form').addEventListener('submit', function(event) {
                event.preventDefault();
                showSpinner(); // Show the loading spinner
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.nocaptcha.sitekey') }}', { action: 'submit' }).then(function(token) {
                        document.getElementById('g-recaptcha-response').value = token;
                        if (validateForm()) {
                            submitForm();
                        } else {
                            hideSpinner(); // Hide the spinner if validation fails
                        }
                    });
                });
            });
        });

        function validateForm() {
            const idNumber = document.getElementById('id_number').value;
            const idNumberPattern = /^[A-Za-z]{1}[0-9]{6}$/;
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

            if (!idNumberPattern.test(idNumber)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'ID number must start with a letter followed by 6 numbers.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
                return false;
            }

            if (password !== passwordConfirmation || !passwordRegex.test(password)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Passwords must match and contain at least 8 characters, including letters and numbers.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
                return false;
            }

            if (!document.getElementById('agreeCheckbox').checked) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'You must agree to the terms and conditions to proceed.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
                return false;
            }

            return true;
        }

        function openTermsModal() {
            document.getElementById('termsModal').style.display = 'block';
        }

        function closeTermsModal() {
            document.getElementById('termsModal').style.display = 'none';
        }

        function showSpinner() {
            document.getElementById('spinner').style.display = 'block';
        }

        function hideSpinner() {
            document.getElementById('spinner').style.display = 'none';
        }

        async function submitForm() {
            const form = document.getElementById('registration-form');
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const data = await response.json();
                hideSpinner(); // Hide the spinner once the response is received

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    }).then(() => {
                        window.location.href = '{{ route('dashboard') }}';
                    });
                } else {
                    const errors = data.errors;
                    if (errors) {
                        if (errors['id_number']) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errors['id_number'],
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                        }
                        if (errors['email']) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errors['email'],
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                        }
                        const otherErrors = Object.values(errors).flat().filter(error => error !== errors['id_number'] && error !== errors['email']).join('<br>');
                        if (otherErrors) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: otherErrors,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Registration failed. Please check your inputs.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                hideSpinner(); // Hide the spinner if an error occurs
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again later.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
        }
    </script>
</x-guest-layout>
