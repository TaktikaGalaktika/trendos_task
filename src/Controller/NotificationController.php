<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class NotificationController extends AbstractController
{
    #[Route('/notifications', name: 'app_notifications', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Extract and validate user_id
        $userId = $request->query->get('user_id');

        if (!$userId) {
            return new JsonResponse(['error' => 'Parameter "user_id" is required'], 400);
        }

        // Fetch User with relations using the EntityManager
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        if ($this->shouldSendNotification($user)) {
            return new JsonResponse([
                'title' => 'Special Offer!',
                'description' => 'We noticed you haven\'t been active. Upgrade to Premium today!',
                'cta' => 'https://trendos.com/'
            ]);
        }

        // Return empty if rules are not met
        return new JsonResponse([]);
    }

    private function shouldSendNotification(User $user): bool
    {
        // Rule "No Android": False if any platform matches 'android'
        foreach ($user->getDevices() as $device) {
            if (strtolower($device->getPlatform()) === 'android') {
                return false;
            }
        }

        // Rule "Not Premium": Check is_premium flag == 0 (false)
        if ($user->isPremium() === true) {
            return false;
        }

        // Rule "Spain Only": Check country_code == 'ES'
        if ($user->getCountryCode() !== 'ES') {
            return false;
        }

        // Rule "Inactivity": Check last_active_at is older than 7 days
        $sevenDaysAgo = new \DateTime('-7 days');
        if ($user->getLastActiveAt() > $sevenDaysAgo) {
            return false;
        }

        return true;
    }
}
