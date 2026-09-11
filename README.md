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
├── public/            → point d'entrée + assets (seul dossier exposé par le serveur)
│   ├── index.php      → front controller (bootstrap, chargement des routes, dispatch)
│   └── .htaccess      → réécriture des URLs vers index.php (Apache)
├── app/
│   ├── Controllers/   → orchestration d'une requête, liaison Vue <-> Services
│   ├── Services/      → règles métier, calculs (panier, paiement, statistiques...)
│   ├── Repositories/  → accès aux données (requêtes SQL, préparées via PDO)
│   ├── Interfaces/    → contrats des repositories (dé-couplage + injection)
│   ├── Models/        → entités métier (hydratées par les repositories)
│   └── Middleware/    → filtres d'accès avants contrôleurs
├── Core/              → noyau de l'architecture MVC maison
│   ├── Router.php     → mappage routes → contrôleur/action + middleware
│   ├── View.php       → rendu des templates + layouts
│   ├── Database.php   → connexion PostgreSQL (PDO, singleton)
│   └── Container.php  → injection automatique des dépendances (Reflection)
├── config/            → helpers globaux + configuration
├── database/          → script.sql (schéma PostgreSQL partagé avec le Module A)
├── routes/            → web.php (déclaration de toutes les routes)
├── views/             → templates (public + dashboard) + partials (pagination, flash)
│   └── partials/      → fragments réutilisables (pagination, drawer, encaissement...)
├── Exceptions/        → exceptions métier (Validation, NotFound, Auth, Stock...)
├── vendor/            → dépendances Composer (vlucas/phpdotenv) — gitignoré
├── .env               → variables d'environnement — gitignoré (voir .env.example)
└── composer.json      → autoload PSR-4 (App\, Core\, Exceptions\) + dépendances
```

### Rôle détaillé de chaque dossier

| Dossier | Rôle |
|---------|------|
| **`public/`** | Point d'entrée HTTP. `index.php` démarre la session, charge Composer, charge `.env`, crée le `Container`, enregistre les bindings (Interfaces → Repositories), charge `routes/web.php` puis appelle `$router->dispatch()`. `.htaccess` redirige toute requête vers `index.php`. |
| **`app/Controllers/`** | Traitent une requête : lisent les entrées (`input()`, `value()`), font appel aux **Services**, puis transmettent les données à une vue via `View::render()`. Retournent un tableau d'attributs (jamais de HTML brut sauf redirections). |
| **`app/Services/`** | Règles métier et logique applicative : calcul des montants, validation du stock, transitions de statut, statistiques du dashboard, etc. Contrôleurs et repositories ne contiennent pas de règles métier. |
| **`app/Repositories/`** | Accès aux données. Requêtes SQL (toujours préparées, PDO) propres à chaque entité (`ProduitRepository`, `CommandeRepository`, `PaiementRepository`...). Retournent des **Models** hydratés ou des tableaux. |
| **`app/Interfaces/`** | Contrats (interfaces) de chaque repository. Permettent l'injection de dépendances et simplifient les tests (mocks). |
| **`app/Models/`** | Entités métier (getters/setters, logique sur l'objet : `estEnRupture()`, `stockFaible()`, `montantRestant()`...). |
| **`app/Middleware/`** | Filtres d'accès exécutés **avant** les contrôleurs : `auth()` (connecté ?), `guest()` (inverse), `role(...)` (rôles autorisés). Lancés par le Router via `runMiddleware()` sur la liste déclarée dans la route. |
| **`Core/`** | Micro-framework maison : `Router` (routing + middleware + CSRF), `View` (templates/layouts + redirections), `Database` (connexion Postgres singleton), `Container` (résolution des dépendances par réflexion). |
| **`config/`** | Scripts chargés au bootstrap : `helpers.php` (fonctions globales `isConnected()`, `hasRole()`, `flash()`, `paginer()`), `config.php` et `validator.php` (règles de validation), `cloudinary.php` (optionnel). |
| **`database/`** | `script.sql` : création du schéma PostgreSQL (tables, séquences, données de test), partagé avec le Module A Java. |
| **`routes/`** | `web.php` : déclaration de toutes les routes avec verbe HTTP, contrôleur/action, et middleware éventuel : `$router->get('/produits', [ProduitController::class, 'index'])`. |
| **`views/`** | Templates PHP. `layouts/public.php` et `layouts/app.php` (dashboard) définissent la structure, `partials/` des fragments réutilisables (pagination, drawer produit, encaissement). Variables transmises par `extract()`. |
| **`Exceptions/`** | Exceptions métier levées par les services/repositories et converties en flash + redirection (ou 404/403) par le Router dans `handleException()`. |

### Le middleware

Les contrôles d'accès sont déclaratifs, directement sur les routes (`routes/web.php`) :

```php
$router->get('/dashboard', [DashboardController::class, 'index'], ['auth', 'role:GERANT,ADMIN']);
$router->post('/commandes', [CommandeController::class, 'store'], ['auth', 'role:CLIENT']);
```

- `auth` → vérifie `isConnected()` (session « user »), sinon redirige vers `/connexion`.
- `guest` → si déjà connecté, redirige vers l'accueil.
- `role:GERANT,ADMIN` → vérifie le rôle de l'utilisateur connecté (via `hasRole()`), sinon renvoie une **403**.

Ils sont exécutés dans `Router::runMiddleware()` avant l'instanciation du contrôleur. L'analogie : un agent de sécurité à la porte du contrôleur.

## Flux complet d'une requête

Exemple avec `GET /produits` :

```
Navigateur
   │  GET /produits
   ▼
public/index.php ──( .htaccess : tout pointe sur index.php )
   │  1. vendor/autoload + .env + session_start()
   │  2. New Container() + bindings (Interface → Repo, via closures)
   │  3. require routes/web.php  → Route enregistrée
   │  4. Router->dispatch('GET', '/produits')
   ▼
Core\Router::dispatch()
   │  1. Boucle sur les routes → match méthode + chemin
   │     (paramètres {id} → (\d+))
   │  2. Si POST → vérification CSRF (_token vs $_SESSION['csrf'])
   │  3. runMiddleware()  →  auth, role:... (403 si refus)
   │  4. Container->make(ProduitController::class)
   │     → résolution auto des dépendances (Reflection)
   ▼
ProduitController::index()
   │  1. $this->produitService->listerTous()  (règles métier)
   │  2. $pagination = paginer($produits, $page)
   │  3. View::render('produits/gestion', $donnees)
   ▼
Core\View::render()
   │  1. extract($donnees) + include views/produits/gestion.php
   │  2. Buffer rendu injecté dans layouts/app.php (+ partials : pagination...)
   ▼
Navigateur  ← réponse HTML complète
```

Le flux passe toujours par **Service → Repository → Base de données** :

```
Contrôleur → Service (règles métier) → Repository (SQL préparé, PDO)
                                              │
                                              ▼
                                   PostgreSQL (saveur221) → Models hydratés
                                              │
                                              ▼
Contrôleur → View::render() → HTML renvoyé au navigateur
```

Erreurs : si un Service lève une `AppException`, le Router la convertit en
flash + redirection (ou 404/403 selon le type d'exception). Toute autre
`Throwable` est interceptée par `public/index.php` → écran **500**.

## Convention de commits

Les commits suivent `feat:`, `fix:`, `docs:`, `refactor:`, ex. :
`feat(commandes): encaissement, statut de paiement et filtrage`.

Branches recommandées : `main`, `develop`, `feature/<module>`.