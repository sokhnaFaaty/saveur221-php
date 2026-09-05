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
        $notifications = $this->notificationService->listerPourRole($role);

        header('Content-Type: application/json');
        echo json_encode([
            'non_lues' => $this->notificationService->compterNonLues($role),
            'notifications' => array_map(fn ($n) => [
                'id' => $n->id, 'message' => $n->message, 'lien' => $n->lien,
                'lue' => $n->lue, 'date' => $n->createdAt,
            ], $notifications),
        ]);
        exit;
    }

    public function markRead(int $id): never
    {
        $this->notificationService->marquerLue($id);
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
}