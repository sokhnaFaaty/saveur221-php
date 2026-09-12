<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\NotificationService;

class NotificationController extends Controller
{
    public function __construct(private NotificationService $notificationService) {}

    // Appelee en JS (fetch) pour peupler la cloche - renvoie du JSON
    public function index(): never
    {
        $role = $_SESSION['user']['role'];

        if ($role === 'CLIENT') {
            $clientId = (int) $_SESSION['user']['id'];
            $notifications = $this->notificationService->listerPourClient($clientId);
            $nonLues = $this->notificationService->compterNonLuesClient($clientId);
        } else {
            $notifications = $this->notificationService->listerPourRole($role);
            $nonLues = $this->notificationService->compterNonLues($role);
        }

        header('Content-Type: application/json');
        echo json_encode([
            'non_lues' => $nonLues,
            'notifications' => array_map(fn ($n) => [
                'id' => $n->id, 'message' => $n->message, 'lien' => $n->lien,
                'lue' => $n->lue, 'date' => $n->createdAt,
            ], $notifications),
        ]);
        exit;
    }

    public function markRead(int $id): never
    {
        if (($_SESSION['user']['role'] ?? '') === 'CLIENT') {
            $this->notificationService->marquerLueClient($id, (int) $_SESSION['user']['id']);
        } else {
            $this->notificationService->marquerLue($id);
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
}