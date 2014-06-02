<?php 

namespace Politizr\Security\User\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;

use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;


use Politizr\Security\User\Model\OAuthUser;


/**
 * Provider de la connexion OAuth
 *
 * @author Lionel Bouzonville
 */
class OAuthUserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface {
    private $serviceContainer;

    public function __contruct($serviceContainer) {
        $this->serviceContainer = $serviceContainer;
    }

    public function loadUserByUsername($username) {
        throw new \Exception('loadByUsername not implemented');
    }
    
    public function supportsClass($class) {
        return $class === 'Politizr\\Security\\User\\Model\\OAuthUser';
    }
    
    public function refreshUser(\Symfony\Component\Security\Core\User\UserInterface $user) {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Unsupported user class "%s"', get_class($user)));
        }
        return $user;
    }
    
    public function loadUserByOAuthUserResponse(UserResponseInterface $response) {
        return new OAuthUser($response);
    }
    
}