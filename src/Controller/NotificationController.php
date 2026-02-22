<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class NotificationController extends AbstractController
{
    #[Route('/notifications', name: 'app_notifications', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // 1. Extract and validate user_id (Task 2.2)
        $userId = $request->query->get('user_id');

        if (!$userId) {
            return new JsonResponse(['error' => 'Parameter "user_id" is required'], 400);
        }

        // 2. Fetch User with relations using the EntityManager (Task 2.3)
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        // 3. Placeholder for Rules (Task 3)
        $notifications = [];

        return new JsonResponse($notifications);
    }
}
