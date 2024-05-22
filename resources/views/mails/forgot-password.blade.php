<x-mail::message>
    # Réinitialisation du mot de passe

    Cher/Chère {{ ucfirst($name) }},

    Vous avez demandé à réinitialiser votre mot de passe. Veuillez cliquer sur le bouton ci-dessous pour continuer.

    Voici le nouveau mot de passe : {{ $token }}
    
    <x-mail::button :url="url('http://127.0.0.1:8000/api/users/reset-password/' . $token)">
        Réinitialiser le mot de passe
    </x-mail::button>

    Si vous n'avez pas demandé cette réinitialisation, vous pouvez ignorer cet e-mail.

    Merci,
    {{ config('app.name') }}
</x-mail::message>


{{-- <x-mail::message>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Réinitialisation du mot de passe</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                background-color: #f9f9f9;
                padding: 20px;
            }

            .container {
                max-width: 600px;
                margin: 0 auto;
                border: 1px solid #ddd;
                border-radius: 5px;
                background-color: #fff;
                padding: 20px;
            }

            h1 {
                color: #333;
            }

            p {
                margin-bottom: 20px;
            }

            .btn {
                display: inline-block;
                padding: 10px 20px;
                background-color: #007bff;
                color: #fff;
                text-decoration: none;
                border-radius: 5px;
            }

            .btn:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Réinitialisation du mot de passe</h1>
            <p>Cher/Chère {{ ucfirst($name) }},</p>

            <p>Vous avez demandé à réinitialiser votre mot de passe. Veuillez cliquer sur le bouton ci-dessous pour continuer :</p>

            <p><a href="{{ url('http://127.0.0.1:8000/api/users/reset/' . $token) }}" class="btn">Réinitialiser le mot de passe</a></p>

            <p>Si vous n'avez pas demandé cette réinitialisation, vous pouvez ignorer cet e-mail.</p>

            <p>Merci,<br>{{ config('app.name') }}</p>
        </div>
    </body>
    </html>
</x-mail::message> --}}

