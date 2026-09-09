# Saveur221 — Application Web (Module B PHP)

Plateforme de gestion du restaurant **Saveur221** : partie Web PHP (MVC) du projet
composée de deux modules complémentaires partageant la même base PostgreSQL :

- **Module A** — Application Java Console (personnel interne)
- **Module B** — Application Web PHP (visiteurs, clients, gérants, administrateurs)

## Technologies

- PHP 8.x (MVC maison : `Core/Router`, `Core/View`, `Core/Database`, `Core/Container`)
- PostgreSQL (PDO)
- Tailwind CSS via CDN + Font Awesome
- `vlucas/phpdotenv` pour la configuration
- Cloudinary (optionnel) pour l'hébergement des images

## Installation

Prérequis : PHP ≥ 8.0, Composer, PostgreSQL.

1. Cloner le projet et installer les dépendances :

   ```bash
   composer install
   ```

2. Créer le fichier de configuration depuis l'exemple :

   ```bash
   cp .env.example .env   # Windows : copy .env.example .env
   ```

3. Remplir `.env` :

   ```env
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_NAME=saveur221
   DB_USER=*********
   DB_PASSWORD=********

   CLOUDINARY_CLOUD_NAME=
   CLOUDINARY_API_KEY=
   CLOUDINARY_API_SECRET=
   ```

4. Importer le script SQL (base partagée avec le module Java) :

   ```bash
   psql -U postgres -d saveur221 -f database/script.sql
   ```

5. Lancer le serveur (racine = dossier `public/`) :

   ```bash
   php -S localhost:8000 -t public
   ```

6. Ouvrir http://localhost:8000

> Si le site est servi dans un sous-dossier, la base URL est détectée automatiquement
> par la vue (`Core/View::baseUrl()`).

## Rôles et accès

| Rôle | Accès |
|------|-------|
| **Visiteur** | Accueil, catalogue, détail produit, recherche, inscription, connexion |
| **CLIENT** | Panier, commandes, historique, profil, avis |
| **GERANT** | Dashboard, catégories, produits, stocks, commandes, paiements, statistiques |
| **ADMIN** | Tous les droits du gérant + gestion du staff, des clients et des avis |

La gestion des droits se fait par middleware (`auth` + `role:...`) sur les routes.

## Fonctionnalités principales

### Public / Client
- Accueil (catégories, plats coup de cœur, témoignages), catalogue (recherche + filtre catégorie), détail produit
- Inscription avec photo de profil, connexion avec « Se souvenir de moi »
- Panier persistant (localStorage), validation de commande (diminution du stock, facture auto)
- Suivi de commande, historique, factures et reçus
- Profil modifiable (infos + mot de passe), avis après retrait (note 1–5, un seul par commande)

### Gérant / Admin
- Dashboard : CA du jour, commandes en cours, alertes stock
- CRUD catégories (avec image optionnelle) et produits (upload image / URL)
- Stocks : rupture / faible / optimal, réapprovisionnement
- Commandes : filtres par statut et statut de paiement, recherche par numéro, encaissement direct
- Caisse : historique des paiements, reçus, statut calculé (Impayée / Partielle / Payée)
- Notifications internes, statistiques

### Admin uniquement
- Gestion du staff (ajout, activation/désactivation, suppression)
- Répertoire des clients, modération des avis

## Règles métier

- Commande d'au moins un article, impossible de dépasser le stock, prix pris en base
- Stock diminué à la commande, restauré à l'annulation
- Catégorie contenant des produits non supprimable
- Avis possible uniquement après retrait, un seul par commande
- Paiement ≤ montant restant ; commande non payée non marquable **RETIREE**
- Compte désactivé = connexion refusée ; emails/téléphones uniques ; mot de passe haché

## Structure du projet

```
Saveur221-php/
├── public/            → point d'entrée + assets
├── app/
│   ├── Controllers/   → traitement des requêtes
│   ├── Services/      → règles métier
│   ├── Repositories/  → accès aux données (SQL)
│   ├── Interfaces/    → contrats des repositories
│   ├── Models/        → entités métier
│   └── Middleware/    → filtres d'accès
├── Core/              → noyau framework (Router, View, Database, Container)
├── config/            → helpers, configuration
├── database/          → script.sql
├── routes/            → web.php (toutes les routes)
├── views/             → templates (public + dashboard)
└── Exceptions/        → exceptions métier
```

## Convention de commits

Les commits suivent `feat:`, `fix:`, `docs:`, `refactor:`, ex. :
`feat(commandes): encaissement, statut de paiement et filtrage`.

Branches recommandées : `main`, `develop`, `feature/<module>`.