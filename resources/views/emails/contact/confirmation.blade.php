<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f6f7fb; margin:0; padding:24px;">
    <div style="max-width:640px; margin:0 auto; background:#ffffff; padding:20px; border-radius:12px;">
        <h2 style="margin:0 0 12px; color:#111827;">Votre message a bien été reçu</h2>

        <p style="margin:0 0 12px; color:#374151;">
            Bonjour {{ $data['full_name'] }},
        </p>

        <p style="margin:0 0 12px; color:#374151;">
            Merci pour votre message. Notre équipe vous répondra dans les meilleurs délais.
        </p>

        <div style="padding:14px; background:#f9fafb; border-radius:10px; color:#111827;">
            <p style="margin:0 0 6px;"><strong>Objet :</strong> {{ $data['subject_label'] }}</p>
            <div style="color:#374151;">{!! nl2br(e($data['message'])) !!}</div>
        </div>

        <p style="margin:16px 0 0; color:#6b7280; font-size:12px;">
            EDGVM — Université de Mahajanga
        </p>
    </div>
</body>
</html>