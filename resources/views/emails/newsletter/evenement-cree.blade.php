@php
    /** @var \App\Models\Evenement $evenement */
    /** @var \App\Models\NewsletterSubscriber|null $notifiable */

    // Image principale : image d'événement si disponible, sinon logo / fallback
    $coverUrl = $evenement->image?->url
        ?? config('app.url') . '/images/edgvm-default-cover.jpg'; // à adapter à ton projet

    // Couleur du badge type
    $typeColor = match($evenement->type) {
        'soutenance' => '#7e22ce',   // violet
        'seminaire'  => '#2563eb',   // bleu
        'conference' => '#16a34a',   // vert
        'atelier'    => '#f97316',   // orange
        default      => '#6b7280',   // gris
    };

    $typeLabel = $evenement->type_texte ?? ucfirst($evenement->type ?? 'Événement');
    $datePeriode = $evenement->periode_aff ?? $evenement->date_fr;
    $heureDebut  = $evenement->heure_debut_aff;
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvel événement EDGVM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Styles simples compatibles email --}}
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #111827;
        }
        a { color: #1d4ed8; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .btn-primary {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 999px;
            background: linear-gradient(90deg,#1d4ed8,#4f46e5);
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none !important;
        }
        .btn-secondary {
            display: inline-block;
            padding: 9px 18px;
            border-radius: 999px;
            border: 1px solid #d1d5db;
            color: #374151 !important;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none !important;
            background-color: #ffffff;
        }
        .text-sm { font-size: 14px; }
        .text-xs { font-size: 12px; }
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            color: #ffffff;
        }
        .chip {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background-color: #f3f4f6;
            font-size: 11px;
            color: #4b5563;
        }
        .muted { color: #6b7280; }
        .footer-link { color: #9ca3af; text-decoration: underline; }
    </style>
</head>
<body>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f3f4f6; padding:24px 0;">
    <tr>
        <td align="center">
            {{-- Container principal --}}
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px; background-color:#ffffff; border-radius:24px; overflow:hidden; box-shadow:0 10px 40px rgba(15,23,42,0.12);">
                {{-- Header / logo --}}
                <tr>
                    <td style="padding:18px 24px 12px 24px; border-bottom:1px solid #e5e7eb;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="left">
                                    <div style="display:flex; align-items:center;">
                                        {{-- Logo EDGVM – remplace l’URL ci-dessous par ton logo --}}
                                        <img src="{{ config('app.url') }}/images/logo-edgvm.png"
                                             alt="EDGVM"
                                             style="height:32px; border-radius:6px; display:block;">
                                        <div style="margin-left:8px;">
                                            <div style="font-size:13px; font-weight:700; color:#111827;">
                                                École Doctorale EDGVM
                                            </div>
                                            <div style="font-size:11px; color:#6b7280;">
                                                Université de Mahajanga
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td align="right">
                                    <span class="chip" style="background-color:#eff6ff; color:#1d4ed8;">
                                        Nouvel événement annoncé
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Hero image --}}
                <tr>
                    <td>
                        <div style="position:relative;">
                            <img src="{{ $coverUrl }}"
                                 alt="Événement EDGVM"
                                 style="width:100%; max-height:260px; object-fit:cover; display:block;">
                            {{-- Overlay dégradé --}}
                            <div style="
                                position:absolute;
                                inset:0;
                                background:linear-gradient(to top,rgba(15,23,42,0.85),rgba(15,23,42,0.15));
                            "></div>

                            {{-- Titre sur l’image --}}
                            <div style="
                                position:absolute;
                                left:24px;
                                right:24px;
                                bottom:20px;
                            ">
                                <div class="badge" style="background-color: {{ $typeColor }};">
                                    {{ strtoupper($typeLabel) }}
                                </div>
                                <div style="margin-top:8px; font-size:22px; line-height:1.3; font-weight:800; color:#f9fafb;">
                                    {{ $evenement->titre }}
                                </div>
                                <div style="margin-top:4px; font-size:13px; color:#e5e7eb;">
                                    {{ $datePeriode }}
                                    @if($heureDebut)
                                        – {{ $heureDebut }}
                                    @endif
                                    @if($evenement->lieu)
                                        • {{ $evenement->lieu }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                {{-- Contenu principal --}}
                <tr>
                    <td style="padding:24px 24px 8px 24px;">
                        <p class="text-sm" style="margin:0 0 12px 0; color:#111827;">
                            Bonjour,
                        </p>

                        <p class="text-sm" style="margin:0 0 16px 0; color:#374151;">
                            Un nouvel événement vient d’être programmé au sein de l’École Doctorale EDGVM.
                            Nous serions ravis de vous y compter parmi les participants.
                        </p>

                        {{-- Bloc infos événement --}}
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                               style="margin-top:8px; margin-bottom:18px; background-color:#f9fafb; border-radius:16px; padding:14px 16px;">
                            <tr>
                                <td width="50%" valign="top" style="padding-right:8px;">
                                    <div style="font-size:11px; text-transform:uppercase; letter-spacing:0.06em; color:#6b7280; margin-bottom:4px;">
                                        Date & heure
                                    </div>
                                    <div style="font-size:13px; font-weight:600; color:#111827; margin-bottom:2px;">
                                        {{ $datePeriode }}
                                    </div>
                                    @if($heureDebut)
                                        <div style="font-size:12px; color:#4b5563;">
                                            À partir de {{ $heureDebut }}
                                        </div>
                                    @endif
                                </td>
                                <td width="50%" valign="top" style="padding-left:8px;">
                                    <div style="font-size:11px; text-transform:uppercase; letter-spacing:0.06em; color:#6b7280; margin-bottom:4px;">
                                        Lieu
                                    </div>
                                    <div style="font-size:13px; color:#111827;">
                                        {{ $evenement->lieu ?: 'Lieu communiqué ultérieurement' }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding-top:10px;">
                                    <div style="font-size:11px; text-transform:uppercase; letter-spacing:0.06em; color:#6b7280; margin-bottom:4px;">
                                        Type d’événement
                                    </div>
                                    <div style="font-size:13px; color:#111827;">
                                        {{ $typeLabel }}
                                        @if($evenement->est_important)
                                            <span class="chip" style="margin-left:6px; background-color:#fef2f2; color:#b91c1c;">
                                                🔥 Important
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </table>

                        {{-- Description --}}
                        @if($evenement->description)
                            <div class="text-sm" style="margin-bottom:18px; color:#374151; line-height:1.6;">
                                {!! nl2br(e(Str::limit(strip_tags($evenement->description), 600))) !!}
                            </div>
                        @endif

                        {{-- Bloc document / inscription --}}
                        @if($evenement->document || $evenement->lien_inscription)
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                                   style="margin-bottom:22px; background-color:#eff6ff; border-radius:14px; padding:12px 14px;">
                                <tr>
                                    <td valign="middle" style="font-size:13px; color:#1e3a8a;">
                                        <div style="font-weight:600; margin-bottom:4px;">
                                            Informations pratiques
                                        </div>

                                        @if($evenement->document)
                                            <div class="text-xs" style="margin-bottom:4px; color:#1d4ed8;">
                                                • Document associé :
                                                <a href="{{ $evenement->document->url }}" style="color:#1d4ed8; font-weight:500;">
                                                    {{ $evenement->document->display_name }}
                                                </a>
                                            </div>
                                        @endif

                                        @if($evenement->lien_inscription)
                                            <div class="text-xs" style="margin-top:2px; color:#1d4ed8;">
                                                • Inscription en ligne :
                                                <a href="{{ $evenement->lien_inscription }}" style="color:#1d4ed8; font-weight:500;">
                                                    compléter le formulaire
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        @endif

                        {{-- Boutons / CTA --}}
                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin-bottom:18px;">
                            <tr>
                                <td>
                                    <a href="{{ $urlEvenements }}" class="btn-primary" target="_blank">
                                        Voir les détails des événements
                                    </a>
                                </td>
                                <td style="width:12px;"></td>
                                <td>
                                    <a href="{{ config('app.url') }}" class="btn-secondary" target="_blank">
                                        Accéder au site EDGVM
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p class="text-xs muted" style="margin:0 0 4px 0;">
                            Cet email vous est envoyé dans le cadre de votre abonnement aux informations de l’École Doctorale EDGVM.
                        </p>
                        <p class="text-xs muted" style="margin:0;">
                            Si vous ne souhaitez plus recevoir ces notifications, vous pouvez nous écrire à
                            <a href="mailto:edgvm@mahajanga-univ.mg" class="footer-link">edgvm@mahajanga-univ.mg</a>.
                        </p>
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="padding:16px 24px 18px 24px; border-top:1px solid #e5e7eb; background-color:#f9fafb;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="left" class="text-xs muted">
                                    &copy; {{ date('Y') }} École Doctorale EDGVM – Université de Mahajanga.
                                </td>
                                <td align="right" class="text-xs muted">
                                    Site : <a href="{{ config('app.url') }}" class="footer-link" target="_blank">{{ config('app.url') }}</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>