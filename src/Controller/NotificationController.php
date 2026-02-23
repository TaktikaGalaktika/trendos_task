<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\NotificationService;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class NotificationController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly NotificationService $notificationService
    ) {}

    #[Route('/notifications', name: 'app_notifications', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $userId = $request->query->get('user_id');

        if (!$userId || !is_numeric($userId)) {
            return new JsonResponse(['error' => 'Valid "user_id" is required'], 400);
        }

        $user = $this->userRepository->findUserWithDevices((int)$userId);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        return new JsonResponse($this->notificationService->getEligibleNotifications($user));
    }
}
