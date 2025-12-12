<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nouveau message</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f6f7fb; margin:0; padding:24px;">
    <div style="max-width:640px; margin:0 auto; background:#ffffff; padding:20px; border-radius:12px;">
        <h2 style="margin:0 0 12px; color:#111827;">Nouveau message — Formulaire de contact EDGVM</h2>

        <p style="margin:0 0 8px; color:#374151;">
            <strong>Nom :</strong> {{ $data['full_name'] }}
        </p>
        <p style="margin:0 0 8px; color:#374151;">
            <strong>Email :</strong> {{ $data['email'] }}
        </p>
        @if(!empty($data['phone']))
            <p style="margin:0 0 8px; color:#374151;">
                <strong>Téléphone :</strong> {{ $data['phone'] }}
            </p>
        @endif
        <p style="margin:0 0 16px; color:#374151;">
            <strong>Objet :</strong> {{ $data['subject_label'] }}
        </p>

        <div style="padding:14px; background:#f9fafb; border-radius:10px; color:#111827;">
            {!! nl2br(e($data['message'])) !!}
        </div>

        <p style="margin:16px 0 0; color:#6b7280; font-size:12px;">
            Astuce : cliquez sur “Répondre” pour répondre directement au visiteur (reply-to configuré).
        </p>
    </div>
</body>
</html>
