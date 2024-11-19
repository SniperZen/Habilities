<x-guest-layout>
<style>
    .container {
        display: flex;
        width: 100%;
        height: 100vh;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        position: relative;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .image-side {
        background-image: url('{{ asset('images/bg.png') }}');
        background-size: cover;
        background-position: center;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 0; 
    }

    .form-side {
        background-color: white;
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        box-sizing: border-box;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); 
        z-index: 1; 
        max-width: 478px;
        width: 90%;
        margin: 0 20px;
        position: relative;
        border-radius: 10px;
    }

    .form-side h2 {
        font-size: 28px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
    }

    .form-side img {
        width: 230px; 
    }

    .form-side p {
        font-size: 18px;
        color: #666;
        margin-bottom: 20px;
        text-align: center;
    }

    .form-side label {
        display: block;
        font-size: 16px;
        color: #333;
        margin-bottom: 5px;
        font-weight: bold;
        text-align: left;
        width: 100%;
    }

    .form-side input[type="email"] {
        width: 100%;
        padding: 15px; 
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 16px;
    }

    .form-side .error {
        font-size: 0.875rem;
        color: #dc2626;
        margin-top: 0.45rem;
        list-style-type: none;
        padding: 0;
        font-weight: bold;
        text-align: center;
        border-color: #f5c6cb;
        position: relative;
        bottom: 11px;
    }

    .form-side button {
        background-color: #74A36B;
        color: white;
        border: none;
        height: 50px; 
        border-radius: 50px;
        cursor: pointer;
        width: 300px; 
        font-size: 18px; 
        font-weight: bold;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
        transition: box-shadow 0.3s ease;
        transition: transform 0.3s ease;
    }

    .input-container {
        position: relative;
        margin-bottom: 10px; 
    }

    .input-container input {
        width: 100%;
        padding: 10px 10px 10px 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 18px; 
        outline: none;
        transition: border-color 0.3s;
    }

    .form-side button:hover {
        transform: scale(1.02);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .input-container label {
        position: absolute;
        top: 40%;
        left: 11px;
        color: #999;
        font-size: 16px; 
        transform: translateY(-50%);
        pointer-events: none;
        transition: all 0.3s;
    }

    .input-container input:focus + label,
    .input-container input:not(:placeholder-shown) + label {
        top: 0;
        left: 8px;
        font-size: 14px;
        color: #74A36B;
        background-color: white;
        width: 50px;
    }

    .success-message {
        position: relative;
        font-size: 0.875rem;
        font-weight: 500;
        color: green;
        bottom: 10px;
        margin-top: 10px;
    }

    .input-container input:focus {
        border-bottom: 1px solid #74A36B;
    }

    input[type="email"]:-webkit-autofill {
        background-color: white !important;
        -webkit-box-shadow: 0 0 0 1000px white inset !important;
        color: black !important;
    }

    .exist {
        display: flex;
        justify-content: center;
        margin-top: 15px;
        gap: 5px;
        font-size: 14px;
    }

    .exist p {
        color: #74A36B;
        font-size: 14px;
        font-weight: 500;
    }

    .exist p:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .image-side {
            display: none;
        }
        .form-side {
            box-shadow: none;
            padding: 40px;
        }

        .form-side h2 {
            font-size: 24px;
        }

        .form-side p {
            font-size: 16px;
        }

        .form-side button {
            width: 100%;
            font-size: 16px;
        }
    }

    @media (max-width: 480px) {
        .image-side {
            display: none;
        }
        .form-side {
            box-shadow: none;
            padding: 30px;
        }

        .form-side h2 {
            font-size: 20px;
        }

        .form-side p {
            font-size: 14px;
        }

        .form-side button {
            height: 40px;
            font-size: 14px;
        }
    }
</style>

    <div class="container">
        <div class="form-side">
            <img src="images/logo.png" alt="">
            <h2>{{ __('Forgot your password?') }}</h2>
            <p>{{ __('Please enter the email you used to sign in.') }}</p>

            <!-- Success Message -->

            @if (session('status'))
                <div class="success-message">{{ session('status') }}</div>
            @endif

            <!-- Error Message -->
            @if ($errors->has('email'))
                <div class="error">{{ $errors->first('email') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="input-container">
                    <!-- <label for="email">{{ __('Email') }}</label> -->
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder=" " />
                    <label for="email">{{ __('Email') }}<span style="color: red;">*</span></label>
                </div>

                <div class="mt-4">
                    <button type="submit">
                        {{ __('Email Password Reset Link') }}
                    </button>
                </div>
                <div class="exist">
                    <p>Already have an account?</p>
                    <a class="signin" href="{{ route('login') }}">
                        <p>Sign in</p>
                    </a>
                </div>
            </form>
        </div>

        <div class="image-side"></div>
    </div>
</x-guest-layout>
