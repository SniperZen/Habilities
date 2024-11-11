    <x-guest-layout>
        <style>
            .container {
                display: flex;
                width: 100%;
                height: 100vh;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            .image-side {
                flex: 1.5;
                background-image: url('{{ asset('images/bg.png') }}');
                background-size: cover;
                background-position: center;
                height: 100vh; /* Ensure it takes full height */
            }

            .form-side {
                flex: 1;
                background-color: white;
                padding: 100px 80px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                box-sizing: border-box;
            }

            .form-side img {
                height: 250px;
            }

            .form-side h2 {
                font-size: 36px;
                font-weight: bold;
                text-align: center;
                margin-bottom: 15px;
            }

            .form-side p {
                font-size: 20px;
                color: #666;
                margin-bottom: 30px;
                text-align: center;
            }

            .form-side button {
                background-color: #74A36B;
                color: white;
                border: none;
                height: 50px;
                border-radius: 50px;
                cursor: pointer;
                width: 100%;
                max-width: 570px;
                font-size: 16px;
                font-weight: bold;
                transition: box-shadow 0.3s ease;
                transition: transform 0.3s ease;
                margin-bottom: 8px;
                padding: 0 14px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
                transition: box-shadow 0.3s ease;
                transition: transform 0.3s ease;
            }

            .form-side button:hover {
                transform: scale(1.02);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);

            }

            .form-side a {
                color: #74A36B;
                font-size: 16px;
                text-decoration: none;
                display: block;
                text-align: center;
            }

            .form-side a:hover {
                text-decoration: underline;
            }

            .space-y-4 {
                display: flex;
                flex-direction: column;
                gap: 15px;
                width: 100%;
                max-width: 500px;
            }

            

            .mt-4{
                display: flex;
                align-items: flex-start;
                flex-direction: row;
                margin-top: 15px;
                gap: 5px;
                a, p{
                    font-size: 16px;
                }
            }

            @media (max-width: 768px) {

            .image-side {
                display:none;
            }
            .form-side {
                padding: 40px 20px;
            }

            .form-side h2 {
                font-size: 24px;
            }

            .form-side p {
                font-size: 16px;
            }

            .form-side button {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .image-side {
                display:none;
            }
            .form-side {
                padding: 30px 10px;
            }

            .form-side h2 {
                font-size: 20px;
            }

            .form-side p {
                font-size: 14px;
            }

            .form-side button {
                height: 45px;
                font-size: 12px;
            }
        }
        </style>

        <div class="container">
            <div class="form-side">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto mb-4"> 
                <h2>Create your account</h2>
                <p>Who will use this account?</p>

                <form method="GET" action="{{ route('register') }}">
                    <div class="space-y-4">
                        <button type="submit" name="account_type" value="self">
                            I want to create an account for myself.
                        </button>
                        <button type="submit" name="account_type" value="child">
                            I want to create an account for my child.
                        </button>
                    </div>
                </form>

                <div class="mt-4">
                    <p> Already have an account?</p>
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                         Sign in
                    </a>
                </div>
            </div>
            
            <div class="image-side"></div>
        </div>
    </x-guest-layout>
