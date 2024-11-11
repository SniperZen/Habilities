<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="images/logo.png" type="image/x-icon">

        <title>Habilities Center for Intervention</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <style>
            * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
            html, body {
            height: 100%;
            overflow: hidden; 
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #b3b3b3;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #8b8b8b;
        }
        </style>
    </head>
    <body >
        <div >

            <div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
