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
        $terme = trim((string) $this->value('q', ''));
        $categories = $terme === '' ? $this->categorieService->listerCategories()
                                     : $this->categorieService->rechercherCategorie($terme);

        return View::render('categories/index', ['title' => 'Categories du menu', 'categories' => $categories], null);
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