<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Interfaces\CommandeRepositoryInterface;
use App\Services\ClientService;
use Core\View;

class ClientController extends Controller
{
    public function __construct(
        private ClientService $clientService,
        private CommandeRepositoryInterface $commandes,
    ) {}

    public function index(): string
    {
        $clients = $this->clientService->listerClients();
        $nbCommandesParClient = [];
        foreach ($clients as $client) {
            $nbCommandesParClient[$client->id] = count($this->commandes->findByClient($client->id));
        }

        return View::render('clients/index', [
            'title' => 'Repertoire des Clients',
            'clients' => $clients,
            'nbCommandesParClient' => $nbCommandesParClient,
        ], 'layouts/dashboard');
    }
}