<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Annonce EDGVM</title>
</head>
<body style="margin:0;background:#f6f7fb;font-family:Arial,Helvetica,sans-serif;">
    <div style="max-width:680px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;padding:22px 22px 10px;border-radius:14px;">
            <div style="font-size:12px;letter-spacing:.14em;text-transform:uppercase;color:#6b7280;">
                EDGVM — Université de Mahajanga
            </div>

            <h1 style="margin:10px 0 8px;font-size:20px;line-height:1.35;color:#111827;">
                {{ $annonce->titre }}
            </h1>

            @if(!empty($notifiable?->nom))
                <p style="margin:0 0 14px;color:#374151;font-size:13px;">
                    Bonjour {{ $notifiable->nom }},
                </p>
            @else
                <p style="margin:0 0 14px;color:#374151;font-size:13px;">
                    Bonjour,
                </p>
            @endif

            @if(!empty($annonce->contenu_html))
                <div style="color:#374151;font-size:14px;line-height:1.7;">
                    {!! $annonce->contenu_html !!}
                </div>
            @else
                <p style="color:#374151;font-size:14px;line-height:1.7;">
                    Une annonce académique est disponible.
                </p>
            @endif

            @if(!empty($mediaUrl))
                <div style="margin-top:18px;padding:14px;border-radius:12px;background:#f9fafb;">
                    <div style="font-size:12px;color:#6b7280;margin-bottom:6px;">
                        Fichier associé
                    </div>
                    <a href="{{ $mediaUrl }}"
                       style="display:inline-block;color:#0f766e;text-decoration:none;font-weight:700;">
                        {{ $mediaName ?? 'Télécharger le fichier' }}
                    </a>
                </div>
            @endif

            @if(!empty($urlAnnonce))
                <div style="margin-top:18px;">
                    <a href="{{ $urlAnnonce }}"
                       style="display:inline-block;background:#0f766e;color:#fff;text-decoration:none;
                              padding:10px 14px;border-radius:10px;font-weight:700;font-size:13px;">
                        Voir l’annonce
                    </a>
                </div>
            @endif

            <div style="margin-top:22px;border-top:1px solid #eef2f7;padding-top:12px;color:#6b7280;font-size:12px;">
                <div>EDGVM — École Doctorale Génie du Vivant et Modélisation</div>
                @if(!empty($urlUnsubscribe))
                    <div style="margin-top:8px;">
                        <a href="{{ $urlUnsubscribe }}" style="color:#6b7280;text-decoration:underline;">
                            Se désabonner
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div style="text-align:center;color:#9ca3af;font-size:11px;margin-top:10px;">
            Cet email a été envoyé automatiquement. Merci de ne pas répondre.
        </div>
    </div>
</body>
</html>
