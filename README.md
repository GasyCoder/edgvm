# Plateforme de digitalisation de l’École Doctorale EDGVM

Ce dépôt contient le code source du projet de **digitalisation de l’École Doctorale EDGVM** (Université de Mahajanga).  
L’objectif principal est de mettre en place un **site web officiel** et une **plateforme numérique** pour la gestion des doctorants, des thèses et des activités scientifiques de l’école doctorale.

---

## 🎯 Objectifs du projet

- Offrir un **site vitrine moderne** pour présenter l’EDGVM, ses équipes et ses formations.
- Dématérialiser le **processus d’inscription en thèse**.
- Mettre à disposition une **banque numérique des thèses** (soutenues et en cours).
- Faciliter le **suivi des doctorants** (parcours, encadrement, soutenances).
- Centraliser les **documents officiels** (règlements, formulaires, guides).
- Valoriser les **activités scientifiques** : séminaires, colloques, écoles d’été, projets de recherche, etc.

---

## 🧩 Fonctionnalités principales (cibles)

### 1. Site vitrine public
- Présentation de l’École Doctorale (mission, historique, organigramme).
- Présentation des **Équipes / EAD** et de leurs thématiques.
- Actualités et évènements (séminaires, appels à communications, soutenances).
- Section “Thèses & Publications” (liste des thèses, mémoires HDR, articles).

### 2. Gestion des inscriptions doctorales
- Formulaire d’inscription en ligne (saisie des informations + dépôt des pièces jointes).
- Génération d’un dossier numérique d’inscription.
- Interface de consultation / traitement des dossiers par le secrétariat et la direction.
- Export des données (Excel/CSV) pour traitement administratif.

### 3. Espace Numérique du Doctorant (END) – version ultérieure
- Compte personnel doctorant (profil, coordonnées, parcours).
- Suivi administratif (inscription annuelle, états de validation).
- Suivi scientifique (encadrants, thème de thèse, rapport annuel, publications).
- Historique des soutenances et décisions.

### 4. Banque numérique des thèses
- Base de données des thèses soutenues (titre, auteur, encadrants, année, résumé, mots-clés).
- Moteur de recherche (par auteur, année, discipline, mots-clés).
- Accès au texte intégral (PDF) lorsque disponible.

### 5. Back-office / Administration
- Gestion des utilisateurs (doctorants, encadrants, administrateurs).
- Gestion des contenus : pages, actualités, documents officiels.
- Paramétrage des années académiques, filières, EAD, etc.
- Statistiques de base (nombre de doctorants, thèses soutenues par année, etc.).

---

## 🏗️ Stack technique (proposition)

> À adapter selon les choix définitifs du projet.

- **Backend** : Laravel (>= 11)
- **Frontend** : Blade + (optionnel) Livewire / Vue.js pour les parties dynamiques
- **Base de données** : MySQL / MariaDB
- **Serveur** : Hébergement mutualisé (type o2switch) ou VPS
- **Autres** :
  - Gestion des fichiers : stockage local ou espace dédié (thèses, pièces jointes)
  - Authentification : système d’auth Laravel (avec rôles et permissions)

---

## 📂 Structure générale (indicative)

- `app/` : logique métier (models, services, contrôleurs)
- `resources/views/` : vues (site public, back-office, END)
- `database/migrations/` : schéma des tables (users, doctorants, thèses, dossiers, actualités…)
- `public/` : fichiers accessibles (assets, documents publics, PDFs de thèses)
- `routes/` : routes web et API

---

## 🚀 Installation (développement)

1. Cloner le dépôt :

   ```bash
   git clone https://github.com/GasyCoder/edgvm.git
   cd projet-edgvm
