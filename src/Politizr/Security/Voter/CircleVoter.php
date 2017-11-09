<?php
namespace Politizr\Security\Voter;

use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PUser;
use Politizr\Model\PCircle;
use Politizr\Model\PCTopic;

class CircleVoter extends Voter
{
    const CIRCLE_DETAIL = 'circle_detail';
    const TOPIC_DETAIL = 'topic_detail';

    /**
     *
     */
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::CIRCLE_DETAIL, self::TOPIC_DETAIL))) {
            return false;
        }

        // only vote on Circle objects inside this voter
        if (!$subject instanceof PCircle && !$subject instanceof PCTopic) {
            return false;
        }

        return true;
    }

    /**
     *
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof PUser) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::CIRCLE_DETAIL:
                return $this->canCircleDetail($subject, $user);
            case self::TOPIC_DETAIL:
                $circle = $subject->getPCircle();
                if (!$circle) {
                    throw new InconsistentDataException(sprintf('Topic %s has no circle!', $subject->getId()));
                }
                return $this->canCircleDetail($circle, $user);
        }

        throw new InconsistentDataException('Voter attribute / subject inconsistent data');
    }

    /**
     * 
     */
    private function canCircleDetail(PCircle $circle, PUser $user)
    {
        if ($user->hasRole('ROLE_CIRCLE_' . $circle->getId())) {
            return true;
        }
        return false;
    }
}