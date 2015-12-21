<?php
namespace Politizr\Security\User\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Politizr\Model\PUserQuery;

/**
 * Public area authentication provider
 *
 * @author Lionel Bouzonville
 */
class PUserProvider implements UserProviderInterface
{
    /**
     *
     * @param string $username
     */
    public function loadUserByUsername($username)
    {
        $user = PUserQuery::create()->filterByUsername($username)->findOne();

        return $user;
    }

    /**
     *
     * @param string $email
     */
    public function loadUserByEmail($email)
    {
        $user = PUserQuery::create()->filterByEmail($email)->findOne();

        return $user;
    }

    /**
     *
     * @param string $user
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof PUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     *
     * @param string $class
     */
    public function supportsClass($class)
    {
        return $class === 'Politizr\Model\PUser';
    }
}
