<x-mail::message>
    # Bonjour {{ $data['name'] }},

    Nous sommes ravis de vous accueillir sur MarketPlace ! Pour activer votre compte et commencer à profiter de tous les
    avantages de notre plateforme, veuillez suivre les étapes ci-dessous :

    <x-mail::button :url="$data['url']">
        Activer mon compte
    </x-mail::button>

    Si vous n'avez pas demandé cette activation, veuillez ignorer cet e-mail.

    Merci,<br>
    {{ config('app.name') }}

    ---

    **MarketPlace Support**

    Email: support@marketplace.com
</x-mail::message>
