<x-guest-layout>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            height: 100vh;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .image-side {
            flex: 2.5;
            background-image: url('images/bg.png');
            background-size: cover;
            background-position: center;
            min-height: 100%;
        }

        .form-side {
            flex: 1;
            background-color: white;
            padding: 5% 10%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            box-sizing: border-box;
        }

        .form-side img {
            height: auto;
            max-height: 250px;
            margin-bottom: 20px;
        }

        .form-side h1 {
            font-size: 2em;
            margin-bottom: 10px;
            text-align: center;
            font-weight: bold;
        }

        .form-side p {
            font-size: 1.25em;
            color: #666;
            margin-bottom: 25px;
            text-align: center;
        }

        form{
            min-width: 270px;
            max-width: 270px;
        }

        .form-side input[type="email"],
        .form-side input[type="password"],
        #password {
            width: 100%;
            max-width: 400px;
            padding: 12px 12px 12px 40px; 
            height: 45px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: white;
            display: flex;
            align-items: center;
        }

        .form-side input[type="email"]:focus{
            border: 1px solid #74A36B;
            outline: none; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            .input-container.icon {
                color: #74A36B;
            }
        }

        .form-side input[type="password"]:focus {
            border: 1px solid #74A36B;
            outline: none; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);

            .input-container.icon2 {
                color: #74A36B;
            }
        }

        .form-side button {
            background-color: #74A36B;
            color: white;
            border: none;
            height: 45px;
            margin-top: 20px;
            border-radius: 50px;
            cursor: pointer;
            width: 100%;
            max-width: 400px;
            font-size: 1.1em;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
            transition: box-shadow 0.3s ease;
            transition: transform 0.3s ease;
        }

        button:hover{
            transform: scale(1.02);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .form-side a {
            color: #74A36B;
            font-size: 16px;
            text-decoration: none;
            display: block;
            text-align: center;
            font-weight: 500;
        }

        .form-side a:hover {
            text-decoration: underline;
        }

        .form-side .links {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            font-size: 1em;
            margin-top: 20px;
            gap: 5px;
        }

        .form-side .links p {
            margin: 0;
            font-size: 16px;
        }

        .undLogBut {
            display: flex;
            justify-content: space-between;
            width: 100%;
            align-items: center;
        }

        .undLogBut div {
            display: flex;
            flex-direction: row;
            align-items: center;
            font-size: 1em;
            gap: 3px;
            margin-right: 5px;
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

        /* .input-container {
            position: relative;
        }
        .label {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: gray;
            pointer-events: none;
            transition: 0.2s ease all;
            background-color: transparent;
        }
        input:focus + .label,
        input:not(:placeholder-shown) + .label,
        select:focus + .label,
        select:not(:placeholder-shown) + .label {
            top: 7px;
            font-size: 12px;
            color: black;
            padding: 0 8px 0 3px;
        } */

        .input-container {
            position: relative;
            margin-bottom: 20px;
            width: 100%;
        }

        .input-container .icon, .icon2 {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #666;
            font-size: 1.1em;
            border-right: 1px solid #ccc;
            padding-right: 7px;
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
        

        


        @media (max-width: 768px) {
            .image-side{
                display: none;
            }
            .form-side {
                padding: 20px;
            }

            .form-side h1 {
                font-size: 1.8em;
            }

            .form-side p {
                font-size: 1em;
            }
        }

        @media (max-width: 480px) {
            .image-side{
                display: none;
            }
            .form-side h1 {
                font-size: 1.5em;
            }

            .form-side p {
                font-size: 0.9em;
            }

            .form-side input[type="email"],
            .form-side input[type="password"],
            .form-side button {
                max-width: 100%;
            }

            .error-message {
                font-size: 0.9em;
            }
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
    const emailInput = document.querySelector('.form-side input[type="email"]');
    const passwordInput = document.querySelector('.form-side input[type="password"]');
    const emailIcon = document.querySelector('.input-container .icon');
    const passwordIcon = document.querySelector('.input-container .icon2');
    const togglePassword = document.getElementById('togglePassword');

    // Change icon color on focus/blur
    emailInput.addEventListener('focus', () => {
        emailIcon.style.color = '#74A36B';
    });
    emailInput.addEventListener('blur', () => {
        emailIcon.style.color = ''; 
    });

    passwordInput.addEventListener('focus', () => {
        passwordIcon.style.color = '#74A36B';
    });
    passwordInput.addEventListener('blur', () => {
        passwordIcon.style.color = ''; 
    });

    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle the icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
});

    </script>


    <div class="container">
        <div class="image-side"></div>
        <div class="form-side">
            <img src="images/logo.png" alt="Logo">
            <h1>Welcome to TherapEase!</h1>
            <p>by Habilities Center for Intervention</p>

            @if (session('status'))
                <div class="error-message">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                <div class="input-container">
                    <i class="fas fa-envelope icon"></i>
                    <input id="email" type="email" name="email"  value="{{ old('email') }}" required autofocus placeholder="Email Address">
                    <!-- <label class="label" for="email">Email Address</label> -->
                </div>

                <div class="input-container">
                    <i class="fas fa-lock icon2"></i>
                    <input id="password" type="password" name="password" required placeholder="Password">
                    <i class="far fa-eye toggle-password" id="togglePassword"></i> <!-- Eye Icon -->
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>


                <div class="undLogBut">
                    <div>
                        <input type="checkbox" name="remember" id="remember_me">
                        <label for="remember_me">Remember Me</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route(name: 'password.request') }}" class="fPass">Forgot Password?</a>
                    @endif
                </div>

                <button type="submit">Login</button>

                <div class="links">
                    <p>Don’t have an account?</p>
                    <a href="{{ route('account.type.selection') }}">Sign up</a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
