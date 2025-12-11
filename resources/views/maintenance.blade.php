@php
    $siteName = $settings->site_name ?? 'EDGVM';
    $message  = $settings->maintenance_message
        ?? "Le site est actuellement en maintenance. Merci de revenir ultérieurement.";
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $siteName }} – Maintenance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-950 flex items-center justify-center">
    <div class="max-w-lg mx-auto bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="mx-auto mb-4 h-12 w-12 rounded-2xl bg-amber-100 flex items-center justify-center">
            <svg class="w-7 h-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v4m0 4h.01M10.29 3.86L3.82 18a1 1 0 00.9 1.45h14.56a1 1 0 00.9-1.45L13.71 3.86a1 1 0 00-1.82 0z" />
            </svg>
        </div>

        <h1 class="text-xl font-bold text-slate-900 mb-2">
            {{ $siteName }} est temporairement en maintenance
        </h1>

        <p class="text-sm text-slate-600 leading-relaxed mb-4">
            {{ $message }}
        </p>

        <p class="text-xs text-slate-400">
            Merci pour votre visite et votre compréhension.  
            Le site sera de nouveau accessible prochainement, le temps pour nous de finaliser les mises à jour.
        </p>
    </div>
</body>
</html>
