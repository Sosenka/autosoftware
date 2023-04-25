<?php

namespace App\ApiBundle\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class MessageVoter extends Voter
{
    const POST_MESSAGE = 'post-message';

    const ALLOWED_ATTRIBUTES = [
        self::POST_MESSAGE,
    ];

    final function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, self::ALLOWED_ATTRIBUTES);
    }

    final function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        //get user

        return match ($attribute) {
            self::POST_MESSAGE => true,
            default => false,
        };
    }
}