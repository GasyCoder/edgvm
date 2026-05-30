<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Situation financière</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f6f7fb; margin:0; padding:24px;">
    <div style="max-width:640px; margin:0 auto; background:#ffffff; padding:24px; border-radius:12px;">
        <h2 style="margin:0 0 4px; color:#115E59;">Situation financière</h2>
        <p style="margin:0 0 16px; color:#6b7280; font-size:13px;">École Doctorale — EDGVM, Université de Mahajanga</p>

        <p style="margin:0 0 12px; color:#374151;">
            Bonjour {{ $data['nom'] }},
        </p>

        <p style="margin:0 0 16px; color:#374151;">
            Veuillez trouver ci-dessous le récapitulatif de votre situation financière
            (droits d'inscription, réglés par virement bancaire) auprès de l'École Doctorale.
        </p>

        <table style="width:100%; border-collapse:collapse; margin:0 0 20px;">
            <tr>
                <td style="padding:12px; background:#f9fafb; border-radius:10px; color:#111827; width:33%;">
                    <div style="font-size:12px; color:#6b7280;">Total dû</div>
                    <div style="font-size:16px; font-weight:bold;">{{ $data['total_du'] }}</div>
                </td>
                <td style="width:8px;"></td>
                <td style="padding:12px; background:#ecfdf5; border-radius:10px; color:#065f46; width:33%;">
                    <div style="font-size:12px; color:#047857;">Encaissé</div>
                    <div style="font-size:16px; font-weight:bold;">{{ $data['total_paye'] }}</div>
                </td>
                <td style="width:8px;"></td>
                <td style="padding:12px; background:{{ $data['reste_brut'] > 0 ? '#fef2f2' : '#f9fafb' }}; border-radius:10px; color:{{ $data['reste_brut'] > 0 ? '#991b1b' : '#111827' }}; width:33%;">
                    <div style="font-size:12px; color:{{ $data['reste_brut'] > 0 ? '#b91c1c' : '#6b7280' }};">Reste à payer</div>
                    <div style="font-size:16px; font-weight:bold;">{{ $data['total_reste'] }}</div>
                </td>
            </tr>
        </table>

        @if (count($data['paiements']) > 0)
            <h3 style="margin:0 0 8px; color:#111827; font-size:14px;">Détail des paiements</h3>
            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                <thead>
                    <tr style="text-align:left; color:#6b7280;">
                        <th style="padding:8px; border-bottom:1px solid #e5e7eb;">Niveau</th>
                        <th style="padding:8px; border-bottom:1px solid #e5e7eb;">Année</th>
                        <th style="padding:8px; border-bottom:1px solid #e5e7eb; text-align:right;">Dû</th>
                        <th style="padding:8px; border-bottom:1px solid #e5e7eb; text-align:right;">Payé</th>
                        <th style="padding:8px; border-bottom:1px solid #e5e7eb; text-align:right;">Reste</th>
                        <th style="padding:8px; border-bottom:1px solid #e5e7eb;">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['paiements'] as $paiement)
                        <tr style="color:#374151;">
                            <td style="padding:8px; border-bottom:1px solid #f3f4f6;">{{ $paiement['niveau'] }}</td>
                            <td style="padding:8px; border-bottom:1px solid #f3f4f6;">{{ $paiement['annee_universitaire'] }}</td>
                            <td style="padding:8px; border-bottom:1px solid #f3f4f6; text-align:right;">{{ $paiement['montant_du'] }}</td>
                            <td style="padding:8px; border-bottom:1px solid #f3f4f6; text-align:right;">{{ $paiement['montant_paye'] }}</td>
                            <td style="padding:8px; border-bottom:1px solid #f3f4f6; text-align:right;">{{ $paiement['reste'] }}</td>
                            <td style="padding:8px; border-bottom:1px solid #f3f4f6;">{{ $paiement['statut'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="margin:0 0 16px; color:#6b7280; font-size:13px;">Aucun paiement n'a encore été enregistré.</p>
        @endif

        <p style="margin:20px 0 0; color:#374151; font-size:13px;">
            Pour toute régularisation, le règlement s'effectue par virement bancaire. Merci de conserver vos justificatifs.
        </p>

        <p style="margin:16px 0 0; color:#9ca3af; font-size:12px;">
            EDGVM — Université de Mahajanga · Message automatique du service financier.
        </p>
    </div>
</body>
</html>
