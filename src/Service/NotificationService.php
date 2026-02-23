<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationService
{
    private const INACTIVITY_DAYS = 7;
    private const TARGET_COUNTRY = 'ES';
    private const EXCLUDED_PLATFORM = 'android';

    private const LOCALE_ES = 'es';
    private const LOCALE_DEFAULT = 'en';

    public function __construct(
        private readonly TranslatorInterface $translator
    ) {}

    /**
     * Logic to determine which notifications a user should receive.
     * @return array<int, array<string, string>>
     */
    public function getEligibleNotifications(User $user): array
    {
        if (!$this->shouldReceiveInactivityOffer($user)) {
            return [];
        }

        // Determine locale: if country is ES, use 'es', otherwise default to 'en'
        $locale = ($user->getCountryCode() === self::TARGET_COUNTRY)
            ? self::LOCALE_ES
            : self::LOCALE_DEFAULT;

        // We wrap it in an array to match the "array of notifications" requirement
        return [[
            'title' => $this->translator->trans('notification.inactivity_offer.title', [], null, $locale),
            'description' => $this->translator->trans('notification.inactivity_offer.description', [], null, $locale),
            'cta' => $this->translator->trans('notification.inactivity_offer.cta', [], null, $locale)
        ]];
    }

    private function shouldReceiveInactivityOffer(User $user): bool
    {
        // Rule: No Android
        foreach ($user->getDevices() as $device) {
            if (str_contains(strtolower($device->getPlatform() ?? ''), self::EXCLUDED_PLATFORM)) {
                return false;
            }
        }

        $lastActive = $user->getLastActiveAt();
        if (null === $lastActive) {
            return false;
        }

        // Rule: Not Premium + Spain + Inactivity
        $oneWeekAgo = new \DateTimeImmutable(sprintf('-%d days', self::INACTIVITY_DAYS));

        return $user->isPremium() === false
            && $user->getCountryCode() === self::TARGET_COUNTRY
            && $lastActive < $oneWeekAgo;
    }
}
