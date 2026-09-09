<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AvisService;
use App\Services\CategorieService;
use App\Services\ProduitService;
use Core\View;

class HomeController extends Controller
{
    public function __construct(
        private CategorieService $categorieService,
        private ProduitService $produitService,
        private AvisService $avisService,
    ) {}

    public function index(): string
    {
        $tous = $this->produitService->listerProduitsDisponibles();

        // Image de la categorie = image du premier produit disponible de la categorie
        // (ex: image du "Pastels au Thon" pour "Entrees & Pastels")
        $imagesParCategorie = [];
        foreach ($tous as $p) {
            if ($p->image !== null && $p->image !== '' && !isset($imagesParCategorie[$p->categorieId])) {
                $imagesParCategorie[$p->categorieId] = $p->image;
            }
        }

        // Ordre des "Coups de coeur" + Plat Signature du header (Thieboudienne en premier)
        $ordre = [
            'Thieboudienne Penda Mbaye',
            'Yassa au Poulet Braisé Maison',
            "Dîner d'Agneau Braisé au Poivre", // grillades
            'Café Touba au Lait de Kaolack',
            'Jus de Bissap Rouge Frais',
        ];

        $plats = [];
        // Plat Signature : toujours la Thiéboudienne (plat national), quelle que soit
        // la variante du libelle (ex: "Thieboudieune", "Thiéboudiène", ...).
        foreach ($tous as $p) {
            if (str_contains(mb_strtolower((string) $p->libelle), 'thieboudien')) {
                $plats[] = $p;
                break;
            }
        }
        // Les autres coups de coeur dans l'ordre recommande, sans doublon.
        foreach ($ordre as $libelle) {
            $candidat = null;
            foreach ($tous as $p) {
                if (mb_strtolower((string) $p->libelle) === mb_strtolower($libelle)) {
                    $candidat = $p;
                    break;
                }
            }
            if ($candidat === null || in_array($candidat->id, array_column($plats, 'id'), true)) {
                continue;
            }
            $plats[] = $candidat;
        }

        // Categories paginees : 5 par page (1 ligne de la grille md:grid-cols-5),
        // meme avec 100 categories la page d'accueil reste legere.
        $categories = $this->categorieService->listerCategories();
        $paginationCategories = paginer($categories, (int) $this->value('page_categories', 1), 5);

        return View::render('home', [
            'title'      => 'Accueil',
            'categories' => $paginationCategories['items'],
            'pageCategories' => $paginationCategories['page'],
            'totalPagesCategories' => $paginationCategories['totalPages'],
            'plats'      => $plats,
            'avis'       => $this->avisService->listerTous(),
            'imagesParCategorie' => $imagesParCategorie,
        ], 'layouts/public');
    }
}