<x-guest-layout>
    <style>
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: url('images/bg.png') no-repeat center center fixed;
            background-size: cover;
        }

        .message-box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .message-box p {
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .message-box .btn-group {
            display: flex;
            justify-content: space-between;
        }

        .message-box .btn-group form {
            margin: 0;
        }

        .message-box .btn-group button,
        .message-box .btn-group x-primary-button {
            padding: 12px;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
            transition: transform 0.3s ease;
            background-color: #74A36B;
            border-radius: 50px;
        }

        .message-box .btn-group x-primary-button {
            background-color: #74A36B;
            color: white;
        }

        .message-box .btn-group x-primary-button:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .message-box .btn-group button {
            color: #007bff;
            background: none;
            text-decoration: underline;
        }

        .message-box .btn-group button:hover {
            color: white;
            transform: scale(1.02);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
    </style>

    <div class="container">
        <div class="message-box">
            <p>
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </p>

            @if (session('status') == 'verification-link-sent')
                <p class="mb-4 font-medium text-sm text-green-600">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </p>
            @endif

            <div class="btn-group mt-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <x-primary-button>
                        {{ __('Resend Verification Email') }}
                    </x-primary-button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
