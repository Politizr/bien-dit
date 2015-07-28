<?php

namespace Politizr\Security\User\Provider;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Politizr\Security\User\Model\OAuthUser;

/**
 * OAuth connexion provider
 *
 * @author Lionel Bouzonville
 */
class OAuthUserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface
{
    private $serviceContainer;

    /**
     *
     */
    public function __contruct($serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     *
     * @param string $username
     */
    public function loadUserByUsername($username)
    {
        throw new \Exception('loadByUsername not implemented');
    }
    
    /**
     *
     * @param string $class
     */
    public function supportsClass($class)
    {
        return $class === 'Politizr\\Security\\User\\Model\\OAuthUser';
    }
    
    /**
     *
     * @param UserInterface $user
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Unsupported user class "%s"', get_class($user)));
        }
        return $user;
    }
    
    /**
     *
     * @param UserResponseInterface $response
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        return new OAuthUser($response);
    }
}
