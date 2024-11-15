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
            top: -8px;
            left: 5px;
            font-size: 12px;
            color: #74A36B;
        }

        .form-box button {
            background-color: #74A36B;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
            transition: box-shadow 0.3s ease;
            transition: transform 0.3s ease;
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


        .form-box button:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
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
                    <input placeholder=" " id="password" type="password" name="password" required autocomplete="new-password">
                    <label for="password">{{ __('Password') }}<span style="color: red;">*</span></label>
                    @error('password')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-container">
                    <input placeholder=" " id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                    <label for="password_confirmation">{{ __('Confirm Password') }}<span style="color: red;">*</span></label>
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
