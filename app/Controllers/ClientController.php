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
        $tous = $this->clientService->listerClients();
        $pagination = paginer($tous, (int) $this->value('page', 1));

        $nbCommandesParClient = [];
        foreach ($tous as $client) {
            $nbCommandesParClient[$client->id] = count($this->commandes->findByClient($client->id));
        }

        return View::render('clients/index', [
            'title' => 'Repertoire des Clients',
            'clients' => $pagination['items'],
            'nbCommandesParClient' => $nbCommandesParClient,
            'page' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
            'vue' => $this->value('vue', 'tableau'),
        ], 'layouts/dashboard');
    }
}