<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\CategorieService;
use App\Services\UploadService;
use Core\View;
use Exceptions\AppException;

class CategorieController extends Controller
{
    public function __construct(
        private CategorieService $categorieService,
        private UploadService $uploads,
    ) {}

    public function index(): string
    {
        $terme = trim((string) $this->value('q', ''));
        $toutes = $terme === '' ? $this->categorieService->listerCategories() : $this->categorieService->rechercherCategorie($terme);
        $pagination = paginer($toutes, (int) $this->value('page', 1));

        return View::render('categories/gestion', [
            'title' => 'Categories du Menu',
            'categories' => $pagination['items'],
            'page' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
            'vue' => $this->value('vue', 'cartes'),
        ], 'layouts/dashboard');
    }

    public function store(): never
    {
        try {
            $image = $this->uploads->upload($_FILES['image'] ?? []);
            $this->categorieService->ajouterCategorie(
                (string) $this->value('libelle', ''),
                $this->value('description'),
                $image
            );
            flash('success', 'Categorie creee avec succes.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        View::redirect('/categories');
    }

    public function create(): string
{
    return View::render('categories/form', ['title' => 'Nouvelle categorie', 'categorie' => null], 'layouts/dashboard');
}

public function edit(int $id): string
{
    $categorie = current(array_filter($this->categorieService->listerCategories(), fn ($c) => $c->id === $id));
    if ($categorie === false) {
        http_response_code(404);
        return View::render('errors/404', ['title' => 'Categorie introuvable'], 'layouts/dashboard');
    }
    return View::render('categories/form', ['title' => 'Modifier', 'categorie' => $categorie], 'layouts/dashboard');
}

    public function update(int $id): never
    {
        try {
            $image = $this->uploads->upload($_FILES['image'] ?? []);
            $this->categorieService->modifierCategorie(
                $id,
                (string) $this->value('libelle', ''),
                $this->value('description'),
                $image
            );
            flash('success', 'Categorie modifiee avec succes.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        View::redirect('/categories');
    }

    public function delete(int $id): never
    {
        try {
            $this->categorieService->supprimerCategorie($id);
            flash('success', 'Categorie supprimee.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        View::redirect('/categories');
    }

    public function corbeille(): string
    {
        $pagination = paginer($this->categorieService->listerCategoriesSupprimees(), (int) $this->value('page', 1));
        return View::render('categories/corbeille', [
            'title' => 'Corbeille des categories',
            'categories' => $pagination['items'],
            'page' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
        ], 'layouts/dashboard');
    }

    public function restaurer(int $id): never
    {
        try {
            $this->categorieService->restaurerCategorie($id);
            flash('success', 'Categorie restauree.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        View::redirect('/categories/corbeille');
    }

    public function supprimerDefinitivement(int $id): never
    {
        try {
            $this->categorieService->supprimerCategorieDefinitivement($id);
            flash('success', 'Categorie supprimee definitivement.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        View::redirect('/categories/corbeille');
    }
}