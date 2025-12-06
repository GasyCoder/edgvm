<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur - EDGVM</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 font-poppins">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8 text-center">
            <!-- Icône erreur -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-4">Lien invalide</h1>
            
            <p class="text-gray-600 mb-8">
                Ce lien de désinscription n'est pas valide ou a expiré.
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