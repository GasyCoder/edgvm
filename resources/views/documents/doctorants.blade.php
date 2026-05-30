@php
    $titres = [
        'admission' => 'Admission en doctorat',
        'attestation' => "Attestation d'inscription",
        'evaluation' => "Fiche d'évaluation pour candidature en doctorat",
        'rapport' => 'Rapport administratif du doctorant',
    ];

    $requis = [
        'admission' => ['nom', 'ead', 'sujet_these'],
        'attestation' => ['nom', 'date_inscription'],
        'evaluation' => ['nom', 'ead'],
        'rapport' => ['nom'],
    ];

    $titre = $titres[$type];
@endphp
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titre }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Times New Roman', Georgia, serif; color: #1f2937; margin: 0; background: #f3f4f6; }
        .toolbar { background: #115E59; color: #fff; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; }
        .toolbar button { background: #fff; color: #115E59; border: 0; border-radius: 8px; padding: 8px 16px; font-weight: 700; cursor: pointer; font-family: Arial, sans-serif; }
        .page { background: #fff; width: 210mm; min-height: 297mm; margin: 16px auto; padding: 24mm 22mm; box-shadow: 0 1px 4px rgba(0,0,0,.1); }
        .doc-header { text-align: center; border-bottom: 2px solid #115E59; padding-bottom: 12px; margin-bottom: 28px; }
        .doc-header .rep { font-size: 12px; letter-spacing: .5px; text-transform: uppercase; color: #374151; }
        .doc-header .inst { font-size: 14px; font-weight: 700; margin-top: 4px; color: #111827; }
        .doc-header .ed { font-size: 13px; margin-top: 2px; color: #115E59; font-weight: 700; }
        h1 { text-align: center; font-size: 20px; text-transform: uppercase; letter-spacing: 1px; margin: 30px 0; text-decoration: underline; }
        p { line-height: 1.8; font-size: 15px; text-align: justify; }
        .ref { font-size: 13px; color: #4b5563; margin-bottom: 20px; }
        .field { font-weight: 700; }
        .signature { margin-top: 70px; text-align: right; font-size: 14px; }
        .signature .ville { text-align: right; margin-bottom: 50px; }
        .insuffisant { border: 1px dashed #b91c1c; background: #fef2f2; color: #991b1b; padding: 18px; border-radius: 8px; text-align: center; font-family: Arial, sans-serif; }
        .grille { width: 100%; border-collapse: collapse; margin: 16px 0; font-size: 14px; }
        .grille th, .grille td { border: 1px solid #9ca3af; padding: 8px 10px; text-align: left; }
        .grille th { background: #f3f4f6; }
        /* Rapport administratif : strictement noir & blanc */
        .bw .toolbar { background: #000; }
        .bw .toolbar button { color: #000; }
        .bw .doc-header { border-bottom-color: #000; }
        .bw .doc-header .ed { color: #000; }
        .bw h1 { color: #000; }
        .bw h2.section { font-size: 13px; text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid #000; padding-bottom: 4px; margin: 24px 0 10px; }
        .bw .grille th, .bw .grille td { border-color: #000; }
        .bw .grille th { background: #fff; }
        .bw .signature { color: #000; }
        @media print {
            body { background: #fff; }
            .toolbar { display: none; }
            .page { width: auto; min-height: auto; margin: 0; box-shadow: none; padding: 18mm; }
            .page + .page { page-break-before: always; }
        }
    </style>
</head>
<body class="{{ $type === 'rapport' ? 'bw' : '' }}">
    <div class="toolbar">
        <span style="font-family: Arial, sans-serif; font-weight: 700;">{{ $titre }} — {{ $doctorants->count() }} document(s)</span>
        <button onclick="window.print()">Imprimer / Enregistrer en PDF</button>
    </div>

    @foreach ($doctorants as $d)
        @php
            $manquants = collect($requis[$type])->filter(fn ($champ) => empty($d[$champ]))->values();
        @endphp
        <div class="page">
            <div class="doc-header">
                <div class="rep">République de Madagascar</div>
                <div class="inst">Université de Mahajanga</div>
                <div class="ed">École Doctorale — EDGVM</div>
            </div>

            @if ($manquants->isNotEmpty())
                <h1>{{ $titre }}</h1>
                <div class="insuffisant">
                    Document non généré pour <strong>{{ $d['nom'] ?: 'ce doctorant' }}</strong> :
                    données manquantes ({{ $manquants->implode(', ') }}).
                </div>
            @elseif ($type === 'admission')
                <h1>Admission en doctorat</h1>
                <p class="ref">Réf. : {{ $d['matricule'] }}</p>
                <p>
                    Le Directeur de l'École Doctorale atteste que
                    <span class="field">{{ $d['nom'] }}</span> (matricule
                    <span class="field">{{ $d['matricule'] }}</span>) est admis(e) à poursuivre des études doctorales
                    de niveau <span class="field">{{ $d['niveau'] }}</span> au sein de l'équipe d'accueil
                    <span class="field">{{ $d['ead'] }}</span>.
                </p>
                <p>
                    Le sujet de thèse retenu est : « <span class="field">{{ $d['sujet_these'] }}</span> »@if ($d['specialite']),
                    spécialité <span class="field">{{ $d['specialite'] }}</span>@endif.
                </p>
            @elseif ($type === 'attestation')
                <h1>Attestation d'inscription</h1>
                <p class="ref">Réf. : {{ $d['matricule'] }}</p>
                <p>
                    Le Directeur de l'École Doctorale atteste que
                    <span class="field">{{ $d['nom'] }}</span> (matricule
                    <span class="field">{{ $d['matricule'] }}</span>) est régulièrement inscrit(e) en thèse de doctorat,
                    niveau <span class="field">{{ $d['niveau'] }}</span>, depuis le
                    <span class="field">{{ $d['date_inscription'] }}</span>@if ($d['ead']),
                    au sein de l'équipe d'accueil <span class="field">{{ $d['ead'] }}</span>@endif.
                </p>
                <p>La présente attestation est délivrée à l'intéressé(e) pour servir et valoir ce que de droit.</p>
            @elseif ($type === 'rapport')
                <h1>Rapport administratif</h1>
                <p class="ref">Réf. : {{ $d['matricule'] }} — Établi le {{ $genereLe }}</p>

                <h2 class="section">1. Informations personnelles</h2>
                <table class="grille">
                    <tr><th style="width:38%;">Nom et prénoms</th><td>{{ $d['nom'] }}</td></tr>
                    <tr><th>Genre</th><td>{{ $d['genre'] === 'femme' ? 'Femme' : 'Homme' }}</td></tr>
                    <tr><th>Date et lieu de naissance</th><td>{{ $d['date_naissance'] ?: '—' }}{{ $d['lieu_naissance'] ? ' à '.$d['lieu_naissance'] : '' }}</td></tr>
                    <tr><th>Adresse e-mail</th><td>{{ $d['email'] ?: '—' }}</td></tr>
                    <tr><th>Téléphone</th><td>{{ $d['phone'] ?: '—' }}</td></tr>
                    <tr><th>Adresse</th><td>{{ $d['adresse'] ?: '—' }}</td></tr>
                </table>

                <h2 class="section">2. Cursus doctoral</h2>
                <table class="grille">
                    <tr><th style="width:38%;">Matricule</th><td>{{ $d['matricule'] }}</td></tr>
                    <tr><th>Niveau</th><td>{{ $d['niveau'] }}</td></tr>
                    <tr><th>Statut administratif</th><td>{{ $d['statut'] }}</td></tr>
                    <tr><th>Date d'inscription</th><td>{{ $d['date_inscription'] ?: '—' }}</td></tr>
                    <tr><th>Équipe d'accueil</th><td>{{ $d['ead'] ?: '—' }}</td></tr>
                    <tr><th>Sujet de thèse</th><td>{{ $d['sujet_these'] ?: '—' }}</td></tr>
                    <tr><th>Spécialité</th><td>{{ $d['specialite'] ?: '—' }}</td></tr>
                </table>

                <h2 class="section">3. Situation financière</h2>
                <table class="grille">
                    <tr>
                        <th style="width:38%;">Total dû</th><td>{{ $d['finances']['total_du'] }}</td>
                    </tr>
                    <tr><th>Total encaissé</th><td>{{ $d['finances']['total_paye'] }}</td></tr>
                    <tr><th>Reste à payer</th><td>{{ $d['finances']['total_reste'] }}</td></tr>
                </table>
                @if (count($d['finances']['lignes']) > 0)
                    <table class="grille">
                        <tr>
                            <th>Niveau</th><th>Année</th><th>Dû</th><th>Payé</th><th>Reste</th><th>Statut</th><th>Date</th>
                        </tr>
                        @foreach ($d['finances']['lignes'] as $ligne)
                            <tr>
                                <td>{{ $ligne['niveau'] }}</td>
                                <td>{{ $ligne['annee'] }}</td>
                                <td>{{ $ligne['du'] }}</td>
                                <td>{{ $ligne['paye'] }}</td>
                                <td>{{ $ligne['reste'] }}</td>
                                <td>{{ $ligne['statut'] }}</td>
                                <td>{{ $ligne['date'] ?: '—' }}</td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <p style="font-size:14px;">Aucun paiement enregistré.</p>
                @endif

                <h2 class="section">4. Observation administrative</h2>
                <p style="font-size:14px; min-height:40px;">{{ $d['observation'] ?: 'Néant.' }}</p>
            @else
                <h1>Fiche d'évaluation — Candidature en doctorat</h1>
                <table class="grille">
                    <tr><th style="width:38%;">Candidat(e)</th><td>{{ $d['nom'] }}</td></tr>
                    <tr><th>Matricule</th><td>{{ $d['matricule'] }}</td></tr>
                    <tr><th>Équipe d'accueil</th><td>{{ $d['ead'] }}</td></tr>
                    <tr><th>Sujet proposé</th><td>{{ $d['sujet_these'] ?: '—' }}</td></tr>
                    <tr><th>Spécialité</th><td>{{ $d['specialite'] ?: '—' }}</td></tr>
                </table>
                <table class="grille">
                    <tr><th style="width:60%;">Critère d'évaluation</th><th>Note / 20</th></tr>
                    <tr><td>Qualité du dossier académique</td><td></td></tr>
                    <tr><td>Pertinence et faisabilité du sujet</td><td></td></tr>
                    <tr><td>Adéquation avec l'équipe d'accueil</td><td></td></tr>
                    <tr><td>Motivation et présentation</td><td></td></tr>
                    <tr><th>Appréciation générale</th><th></th></tr>
                </table>
            @endif

            @if ($manquants->isEmpty())
                <div class="signature">
                    <div class="ville">Mahajanga, le {{ $genereLe }}</div>
                    Le Directeur de l'École Doctorale
                </div>
            @endif
        </div>
    @endforeach

    <script>
        window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 400); });
    </script>
</body>
</html>
