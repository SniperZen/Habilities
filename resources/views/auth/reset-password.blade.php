<x-guest-layout>
    <style>
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-image: url('{{ asset('images/bg.png') }}');
            background-size: cover;
        }

        .form-box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-box h1 {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .input-container {
            position: relative;
            margin-bottom: 20px;
        }

        .input-container input {
            width: 100%;
            padding: 8px 8px 8px 5px;
            border: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;
        }

        .input-container input:focus {
            border-bottom: 1px solid #74A36B;
        }

        .input-container label {
            position: absolute;
            top: 50%;
            left: 5px;
            color: #999;
            font-size: 14px;
            transform: translateY(-50%);
            pointer-events: none;
            transition: all 0.3s;
        }

        .input-container input:focus + label,
        .input-container input:not(:placeholder-shown) + label {
            top: 0;
            left: 10px;
            width: auto;
            padding-left: 2px;
            padding-right:3px;
            font-size: 12px;
            color: #74A36B;
            background-color: white;
        }

        .form-box button {
            background-color: #74A36B;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: 500;
            margin-top: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .mt-2 {
            color: red;
            border-color: #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            min-width: 300px;
            max-width: 400px;
            text-align: center;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            font-size: 1.1em;
        }

        .form-box input[type="text"], .form-box input[type="email"], .form-box input[type="date"], .form-box input[type="tel"], .form-box input[type="password"], .form-box select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .password-restrictions {
            display: none; /* Initially hidden */
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            font-size: 14px;
            color: #555;
        }

        .password-restrictions p {
            margin: 0 0 5px;
            font-weight: bold;
        }

        .password-restrictions ul {
            padding-left: 20px;
            margin: 0;
        }

        .password-restrictions ul li {
            margin-bottom: 5px;
        }


        .form-box button:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .check-icon {
            font-weight: bold;
            margin-right: 5px;
            color: red; /* Default color for failed checks */
        }

        @media (max-width: 768px) {
            .form-box {
                padding: 30px;
            }

            .form-box h1 {
                font-size: 20px;
            }
        }

        @media (max-width: 480px) {
            .form-box {
                padding: 20px;
            }

            .form-box h1 {
                font-size: 18px;
            }

            .form-box button {
                font-size: 14px;
            }
        }
    </style>

<head>
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    </head>

    <div class="container">
        <div class="form-box">
            <h1>{{ __('Reset Password') }}</h1>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="input-container">
                    <input placeholder=" " id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                    <label for="email">{{ __('Email') }}<span style="color: red;">*</span></label>
                    @error('email')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-container">
                    <input
                        placeholder=" "
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        onfocus="showRestrictions()"
                        onblur="hideRestrictions()"
                    />
                    <label for="password">Password<span style="color: red;">*</span></label>
                    <i class="far fa-eye toggle-password" onclick="togglePasswordVisibility('password', this)"></i> <!-- Eye Icon -->
                    <div class="error-message">{{ $errors->first('password') }}</div>
                </div>

                <div id="password-restrictions" class="password-restrictions" style="display: none;">
                    <p>Password must meet the following criteria:</p>
                    <span id="uppercase-check" class="check-icon">✕</span> Contain at least one uppercase letter (A-Z)<br>
                    <span id="lowercase-check" class="check-icon">✕</span> Contain at least one lowercase letter (a-z)<br>
                    <span id="number-check" class="check-icon">✕</span> Contain at least one number (0-9)<br>
                    <span id="special-char-check" class="check-icon">✕</span> Contain at least one special character (~`!@#$%^&*()-_+={}[]|\;:"<>,./?)<br>
                    <span id="min-length-check" class="check-icon">✕</span> Be at least 8 characters long
                </div>

                <script>
                    // Update password checks dynamically
                    document.getElementById('password').addEventListener('input', function () {
                        const password = this.value;

                        // Check conditions
                        const hasUpperCase = /[A-Z]/.test(password);
                        const hasLowerCase = /[a-z]/.test(password);
                        const hasNumber = /[0-9]/.test(password);
                        const hasSpecialChar = /[~`!@#$%^&*()\-_+={}[\]|\\:;"'<>,./?]/.test(password);
                        const hasMinLength = password.length >= 8;

                        // Update the UI
                        updateCheck('uppercase-check', hasUpperCase);
                        updateCheck('lowercase-check', hasLowerCase);
                        updateCheck('number-check', hasNumber);
                        updateCheck('special-char-check', hasSpecialChar);
                        updateCheck('min-length-check', hasMinLength);
                    });

                    // Function to update checks
                    function updateCheck(elementId, isValid) {
                        const element = document.getElementById(elementId);
                        if (isValid) {
                            element.textContent = '✔';
                            element.style.color = 'green';
                        } else {
                            element.textContent = '✕';
                            element.style.color = 'red';
                        }
                    }

                    // Show password restrictions
                    function showRestrictions() {
                        document.getElementById('password-restrictions').style.display = 'block';
                    }

                    // Hide password restrictions
                    function hideRestrictions() {
                        document.getElementById('password-restrictions').style.display = 'none';
                    }

                    // Toggle password visibility
                    function togglePasswordVisibility(inputId, icon) {
                        const input = document.getElementById(inputId);
                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.classList.replace('fa-eye', 'fa-eye-slash');
                        } else {
                            input.type = 'password';
                            icon.classList.replace('fa-eye-slash', 'fa-eye');
                        }
                    }
                </script>

                <div class="input-container">
                    <input
                        placeholder=" "
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                    <label for="password_confirmation">{{ __('Confirm Password') }}<span style="color: red;">*</span></label>
                    <i class="far fa-eye toggle-password" onclick="togglePasswordVisibility('password_confirmation', this)"></i> <!-- Eye Icon -->
                    @error('password_confirmation')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="submit">{{ __('Reset Password') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
