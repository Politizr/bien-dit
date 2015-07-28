<?php

namespace Politizr\Security\User\Model;

use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUser as BaseOAuthUser;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

/**
 * Objet user associé à une connexion OAuth
 *
 * @author Lionel Bouzonville
 */
class OAuthUser extends BaseOAuthUser
{
    
    protected $data;
    
    /**
     *
     */
    public function __construct(UserResponseInterface $response)
    {
        parent::__construct($response->getUsername());
        
        $this->data = array(
            'provider'=>$response->getResourceOwner()->getName(),
            'providerId'=>$response->getUsername()
        );
        
        $vars = array(
            'nickname',
            'realname',
            'email',
            'profilePicture',
            'accessToken',
            'refreshToken',
            'tokenSecret',
            'expiresIn',
        );

        foreach ($vars as $v) {
            $fct = 'get'.ucfirst($v);
            $this->data[$v] = $response->$fct();
        }
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getRoles()
    {
        return array('ROLE_OAUTH_USER');
    }
}
