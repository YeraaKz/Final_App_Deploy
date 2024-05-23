<?php

namespace App\Security\Voter;

use App\Entity\ItemsCollection;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ItemsCollectionVoter extends Voter
{
    public const EDIT = 'POST_EDIT';

    protected function supports(string $attribute, mixed $subject): bool
    {

        return in_array($attribute, [self::EDIT])
            && $subject instanceof ItemsCollection;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var ItemsCollection $collection */
        $collection = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($collection, $user);

        }

        return false;
    }

    private function canEdit(ItemsCollection $collection, User $user): bool
    {
        return $user === $collection->getUser();
    }
}
