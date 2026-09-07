<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\CategorieService;
use Core\View;
use Exceptions\AppException;

class CategorieController extends Controller
{
    public function __construct(private CategorieService $categorieService) {}

    public function index(): string
    {
        if (hasRole('GERANT') || hasRole('ADMIN')) {
        $terme = trim((string) $this->value('q', ''));
        $categories = $terme === '' ? $this->categorieService->listerCategories() : $this->categorieService->rechercherCategorie($terme);
        return View::render('categories/gestion', ['title' => 'Categories du Menu', 'categories' => $categories], 'layouts/dashboard');
    }

    return View::render('categories/liste-publique', ['title' => 'Categories', 'categories' => $this->categorieService->listerCategories()], 'layouts/public');
    }

    public function store(): never
    {
        try {
            $this->categorieService->ajouterCategorie(
                (string) $this->value('libelle', ''),
                $this->value('description')
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
    $categorie = $this->categorieService->listerCategories();
    $categorie = current(array_filter($categorie, fn ($c) => $c->id === $id)) ?: null;
    return View::render('categories/form', ['title' => 'Modifier', 'categorie' => $categorie], 'layouts/dashboard');
}

    public function update(int $id): never
    {
        try {
            $this->categorieService->modifierCategorie(
                $id,
                (string) $this->value('libelle', ''),
                $this->value('description')
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
}