<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réabonnement réussi - EDGVM</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 font-poppins">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8 text-center">
            <!-- Icône succès -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-4">🎉 Bienvenue à nouveau !</h1>
            
            <p class="text-gray-600 mb-6">
                Vous êtes à nouveau abonné(e) à notre newsletter.
                <br>
                <span class="text-sm text-gray-500">{{ $subscriber->email }}</span>
            </p>

            <p class="text-gray-600 mb-8">
                Vous recevrez désormais toutes nos actualités importantes.
            </p>

            <a href="{{ route('home') }}" 
               class="inline-block px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold">
                Retour à l'accueil
            </a>

            <div class="mt-8 pt-6 border-t border-gray-200 text-xs text-gray-500">
                <p>EDGVM - École Doctorale Génie du Vivant et Modélisation</p>
                <p class="mt-1">Université de Mahajanga</p>
            </div>
        </div>
    </div>
</body>
</html>