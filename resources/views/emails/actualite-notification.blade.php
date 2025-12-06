<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $actualite->titre }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #059669 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .badge {
            display: inline-block;
            background: #ef4444;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background: #009130ff;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }
        .unsubscribe {
            color: #6b7280;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">🎓 EDGVM</h1>
        <p style="margin: 5px 0 0 0;">École Doctorale Génie du Vivant et Modélisation</p>
    </div>

    <div class="content">
        @if($actualite->est_important)
            <span class="badge">🔥 IMPORTANT</span>
        @endif

        <h2 style="margin-top: 0; color: #1e40af;">{{ $actualite->titre }}</h2>

        @if($actualite->image)
            <img src="{{ $actualite->image->url }}" alt="{{ $actualite->titre }}" class="image">
        @endif

        <div style="color: #4b5563;">
            {!! Str::limit(strip_tags($actualite->contenu), 200) !!}
        </div>

        <a href="{{ route('actualites.show', $actualite) }}" class="button">
            Lire la suite →
        </a>

        <div style="margin-top: 20px; padding: 15px; background: white; border-left: 4px solid #1e40af; border-radius: 5px;">
            <p style="margin: 0; font-size: 14px; color: #6b7280;">
                <strong>Catégorie :</strong> {{ $actualite->category->nom ?? 'Non catégorisé' }}<br>
                <strong>Publié le :</strong> {{ $actualite->date_publication->format('d/m/Y') }}
            </p>
        </div>
    </div>

    <div class="footer">
        <p>Bonjour {{ $subscriber->nom ?? $subscriber->email }},</p>
        <p>Vous recevez cet email car vous êtes abonné(e) à notre newsletter.</p>
        <p>
            <a href="{{ route('newsletter.unsubscribe', $subscriber->token) }}" class="unsubscribe">
                Se désabonner
            </a>
        </p>
        <p>&copy; {{ date('Y') }} EDGVM - Tous droits réservés</p>
    </div>
</body>
</html>