
<x-guest-layout>
    <!-- Custom container to wrap the form and testimonial section -->
    <div class="container">
        <!-- Login Form Container -->
        <div class="login-form">
            <div class="header">
                <label class="title">{{ __('Sign in') }}</label>
                <!-- <p class="description">{{ __('Create your account in no time and enjoy our best online courses for free.') }}</p> -->
            </div>
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="input_container">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512" ><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M64 112c-8.8 0-16 7.2-16 16v22.1L220.5 291.7c20.7 17 50.4 17 71.1 0L464 150.1V128c0-8.8-7.2-16-16-16H64zM48 212.2V384c0 8.8 7.2 16 16 16H448c8.8 0 16-7.2 16-16V212.2L322 328.8c-38.4 31.5-93.7 31.5-132 0L48 212.2zM0 128C0 92.7 28.7 64 64 64H448c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z"/></svg>
                    <!-- <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M7 8.5L9.94202 10.2394C11.6572 11.2535 12.3428 11.2535 14.058 10.2394L17 8.5" stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M2.01577 13.4756C2.08114 16.5412 2.11383 18.0739 3.24496 19.2094C4.37608 20.3448 5.95033 20.3843 9.09883 20.4634C11.0393 20.5122 12.9607 20.5122 14.9012 20.4634C18.0497 20.3843 19.6239 20.3448 20.7551 19.2094C21.8862 18.0739 21.9189 16.5412 21.9842 13.4756C22.0053 12.4899 22.0053 11.5101 21.9842 10.5244C21.9189 7.45886 21.8862 5.92609 20.7551 4.79066C19.6239 3.65523 18.0497 3.61568 14.9012 3.53657C12.9607 3.48781 11.0393 3.48781 9.09882 3.53656C5.95033 3.61566 4.37608 3.65521 3.24495 4.79065C2.11382 5.92608 2.08114 7.45885 2.01576 10.5244C1.99474 11.5101 1.99475 12.4899 2.01577 13.4756Z" stroke="#141B34" stroke-width="1.5" stroke-linejoin="round"></path>
                    </svg> -->
                    <x-text-input id="email" class="input_field" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@mail.com"/>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="input_container mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"/></svg>
                    <!-- <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M18 11.0041C17.4166 9.91704 16.273 9.15775 14.9519 9.0993C13.477 9.03404 11.9788 9 10.329 9C8.67911 9 7.18091 9.03404 5.70604 9.0993C3.95328 9.17685 2.51295 10.4881 2.27882 12.1618C2.12602 13.2541 2 14.3734 2 15.5134C2 16.6534 2.12602 17.7727 2.27882 18.865C2.51295 20.5387 3.95328 21.8499 5.70604 21.9275C6.42013 21.9591 7.26041 21.9834 8 22" stroke="#141B34" stroke-width="1.5" stroke-linecap="round"></path>
                        <path d="M6 9V6.5C6 4.01472 8.01472 2 10.5 2C12.9853 2 15 4.01472 15 6.5V9" stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M21.2046 15.1045L20.6242 15.6956V15.6956L21.2046 15.1045ZM21.4196 16.4767C21.7461 16.7972 22.2706 16.7924 22.5911 16.466C22.9116 16.1395 22.9068 15.615 22.5804 15.2945L21.4196 16.4767ZM18.0228 15.1045L17.4424 14.5134V14.5134L18.0228 15.1045ZM18.2379 18.0387C18.5643 18.3593 19.0888 18.3545 19.4094 18.028C19.7299 17.7016 19.7251 17.1771 19.3987 16.8565L18.2379 18.0387ZM14.2603 20.7619C13.7039 21.3082 12.7957 21.3082 12.2394 20.7619L11.0786 21.9441C12.2794 23.1232 14.2202 23.1232 15.4211 21.9441L14.2603 20.7619ZM15.4394 18.0159L18.0228 15.1045L17.4424 14.5134L14.859 17.4248L15.4394 18.0159ZM22.5804 15.2945L19.997 12.3831L19.4166 12.9742L22 15.8856L22.5804 15.2945ZM19.3987 16.8565L14.2603 20.7619L15.4211 21.9441L20.5596 18.0387L19.3987 16.8565ZM17.4424 15.6956L18.0228 16.2867L20.6242 13.5134L20.0438 12.9222L17.4424 15.6956ZM20.6242 16.2867C20.3064 16.6091 19.7759 16.6098 19.4534 16.292L18.0228 14.8614L17.4424 15.4525L18.873 16.8831C19.7805 17.7907 21.3025 17.792 22.2101 16.8845L20.6242 15.2994V16.2867ZM19.4166 12.3831C18.4212 11.3842 17.1371 11.0348 15.9782 11.0054L15.7754 11.9965C16.6385 12.0218 17.5288 12.3286 18.2456 13.0383L19.4166 12.3831ZM15.9782 11.0054C14.7165 10.9741 13.3836 10.948 12 10.948C10.6164 10.948 9.28348 10.9741 8.02175 11.0054L8.22455 11.9965C9.36172 11.9672 10.5882 11.948 12 11.948C13.4118 11.948 14.6383 11.9672 15.7754 11.9965L15.9782 11.0054ZM8.02175 11.0054C6.86289 11.0348 5.5788 11.3842 4.58338 12.3831L5.75437 13.0383C6.47124 12.3286 7.36152 12.0218 8.22455 11.9965L8.02175 11.0054ZM4.58338 12.3831L2 15.2945L2.5804 15.8856L5.16474 12.9742L4.58338 12.3831ZM8.22455 11.9965C7.06191 11.9662 5.774 12.3832 4.7804 13.0383L5.9514 13.6935C6.56851 13.2282 7.65117 12.9375 8.22455 12.9486L8.22455 11.9965ZM19.9984 12.9685L15.7754 16.2867L16.3558 16.8778L20.5788 13.5595L19.9984 12.9685ZM15.9782 12.9486L12.7544 16.2669L13.3348 16.858L16.5586 13.5397L15.9782 12.9486ZM12.7544 16.2669C12.134 16.8778 11.226 16.8925 10.5941 16.292L9.43329 15.1098L8.27245 16.292C9.47327 17.4711 11.4141 17.4711 12.6149 16.292L12.7544 16.2669ZM9.43329 15.1098L11.7648 12.9486L11.1844 12.3575L8.8529 14.5187L9.43329 15.1098ZM11.7648 12.9486L7.54178 16.2669L8.12218 16.858L12.3452 13.5397L11.7648 12.9486ZM11.1844 12.3575L10.5931 12.9486L9.43229 12.9486L9.43229 14.0095L10.5931 15.1917L11.1844 14.6005L10.0246 13.4092L11.1844 12.3575ZM14.4394 13.5397L12.7544 14.6005L13.9152 15.1917L15.9782 12.9486L14.4394 12.3575V13.5397ZM10.5931 12.9486L8.27245 10.6378L7.54178 10.8867L9.43329 12.9486H10.5931ZM9.43329 12.9486L7.54178 10.8867L5.9514 12.5534L7.54178 10.8867H8.12218L9.43329 12.9486H10.5931ZM7.54178 10.8867L5.9514 12.5534L6.7804 13.2837L8.27245 10.6378L7.54178 10.8867H5.9514ZM6.7804 13.2837L5.18939 11.6169L2.5804 14.5222L2.1598 14.9885L5.18939 11.6169H6.7804ZM5.18939 11.6169L2.1598 14.9885L1.57936 14.3974L5.16474 11.4842L5.18939 11.6169ZM8.27245 11.0054L8.02175 11.9965H7.70076L6.7804 10.6378H8.27245V11.0054ZM8.27245 10.6378L6.7804 11.9965H7.54178H8.27245V10.6378Z" stroke="#141B34" stroke-width="1.5" stroke-linecap="round"></path>
                    </svg> -->
                    <x-text-input id="password" class="input_field" type="password" name="password" required autocomplete="current-password" placeholder="password"/>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span> 
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-primary-button class="ml-4">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
                <div>
                <p>Don't have an account? </p>
                    <a href="">register</a>
                </div>
            </form>
        </div>
        <div>
        <img src="{{ asset('images/pilarLogo.png') }}" alt="Login Image">
        </div>

        <!-- New Container for the Image -->
        <!-- <div class="image-container">
            <img src="https://via.placeholder.com/300" alt="Sample Image">
        </div> -->
    </div>
</x-guest-layout>
