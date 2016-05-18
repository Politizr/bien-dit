<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;

use Symfony\Component\EventDispatcher\GenericEvent;

use Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use TwitterAPIExchange;
use Google_Client;
// use Google_Service_Plus;
use Google_Service_Oauth2;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\OrderConstants;
use Politizr\Constant\UserConstants;
use Politizr\Constant\PathConstants;
use Politizr\Constant\ReputationConstants;

use Politizr\Model\PUser;

use Politizr\Model\POrderQuery;
use Politizr\Model\PUserQuery;

/**
 * Functional service for security management.
 *
 * @author Lionel Bouzonville
 */
class SecurityService
{
    private $securityTokenStorage;
    private $encoderFactory;
    
    private $session;
    private $kernel;
    
    private $router;
    
    private $eventDispatcher;
    
    private $usernameCanonicalizer;
    private $emailCanonicalizer;
    
    private $userManager;
    private $orderManager;

    private $globalTools;

    private $facebookClientId;
    private $facebookClientSecret;
    private $twitterApiKey;
    private $twitterApiSecret;
    private $googleClientId;
    private $googleClientSecret;

    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.encoder_factory
     * @param @session
     * @param @kernel
     * @param @router
     * @param @event_dispatcher
     * @param @fos_user.util.username_canonicalizer
     * @param @fos_user.util.email_canonicalizer
     * @param @politizr.manager.user
     * @param @politizr.manager.order
     * @param @politizr.tools.global
     * @param "%facebook_client_id%"
     * @param "%facebook_client_secret%"
     * @param "%twitter_api_key%"
     * @param "%twitter_api_secret%"
     * @param "%google_client_id%"
     * @param "%google_client_secret%"
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $encoderFactory,
        $session,
        $kernel,
        $router,
        $eventDispatcher,
        $usernameCanonicalizer,
        $emailCanonicalizer,
        $userManager,
        $orderManager,
        $globalTools,
        $facebookClientId,
        $facebookClientSecret,
        $twitterApiKey,
        $twitterApiSecret,
        $googleClientId,
        $googleClientSecret,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->encoderFactory = $encoderFactory;

        $this->session = $session;
        $this->kernel = $kernel;

        $this->router = $router;

        $this->eventDispatcher = $eventDispatcher;

        $this->usernameCanonicalizer = $usernameCanonicalizer;
        $this->emailCanonicalizer = $emailCanonicalizer;

        $this->userManager = $userManager;
        $this->orderManager = $orderManager;

        $this->globalTools = $globalTools;

        $this->facebookClientId = $facebookClientId;
        $this->facebookClientSecret = $facebookClientSecret;
        $this->twitterApiKey = $twitterApiKey;
        $this->twitterApiSecret = $twitterApiSecret;
        $this->googleClientId = $googleClientId;
        $this->googleClientSecret = $googleClientSecret;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                        PRIVATE FUNCTIONS                                                 */
    /* ######################################################################################################## */

    /**
     * Soft firewall public connection
     *
     * @param PUser $user
     * @param string $firewalll
     */
    private function doPublicConnection(PUser $user, $firewall = 'user_area')
    {
        $token = new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
        $this->securityTokenStorage->setToken($token);
        $this->eventDispatcher->dispatch(AuthenticationEvents::AUTHENTICATION_SUCCESS, new AuthenticationEvent($token));
    }

    /**
     * Compute redirection URL depending on user role
     *
     * @param PUser $user
     * @return string
     */
    private function computeRedirectUrl(PUser $user)
    {
        $redirectUrl = null;
        if ($user->hasRole('ROLE_PROFILE_COMPLETED')) {
            $user->setLastLogin(new \DateTime());
            $user->save();

            if ($user->getQualified() && $user->getPUStatusId() == UserConstants::STATUS_ACTIVED) {
                $redirectUrl = $this->router->generate('HomepageE');
            } elseif ($user->hasRole('ROLE_CITIZEN')) {
                $redirectUrl = $this->router->generate('HomepageC');
            } else {
                throw new InconsistentDataException('Qualified user is not activ and has no citizen role');
            }
        } elseif ($user->hasRole('ROLE_CITIZEN_INSCRIPTION')) {
            $redirectUrl = $this->router->generate('InscriptionContact');
        } elseif ($user->hasRole('ROLE_ELECTED_INSCRIPTION')) {
            $redirectUrl = $this->router->generate('InscriptionElectedOrder');
        } else {
            throw new InconsistentDataException('No valid role for user');
        }

        return $redirectUrl;
    }

    /**
     * Manage download & update user with an oAuth profile photo
     *
     * @param PUser $user
     * @param string $oAuthFileUrl
     * @return boolean
     */
    private function manageOAuthProfilePhoto(PUser $user, $oAuthFileUrl)
    {
        if ($oAuthFileUrl) {
            $lastDotPos = strrpos($oAuthFileUrl, '.');
            if ($lastDotPos) {
                $extension = substr($oAuthFileUrl, ($lastDotPos + 1));
                $fileName = $this->globalTools->downloadFileFromUrl(
                    $oAuthFileUrl,
                    $this->kernel->getRootDir() . PathConstants::KERNEL_PATH_TO_WEB . PathConstants::USER_UPLOAD_WEB_PATH,
                    $user->computeFileName()
                );
                if ($fileName) {
                    $user->setFileName($fileName);

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Retrieve Facebook API data to update user object.
     * https://developers.facebook.com/docs/php/gettingstarted/5.0.0
     *
     * @param integer $providerId
     * @param string $accessToken
     * @param PUser $user
     * @return boolean
     */
    private function manageFacebookApiExtraData($providerId, $accessToken, PUser $user)
    {
        $facebookClient = new Facebook\Facebook([
          'app_id' => $this->facebookClientId,
          'app_secret' => $this->facebookClientSecret,
          'default_graph_version' => 'v2.6',
          'default_access_token' => $accessToken, // optional
        ]);

        try {
            // Get the Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.

            // id,name,email,birthday,picture.type(square)
            $response = $facebookClient->get(
                sprintf('/%s?fields=gender,first_name,last_name,link,birthday,about,bio,location,website,is_verified', $providerId)
            );
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            $this->logger->error(sprintf('FacebookResponseException - msg = %s', $e->getMessage()));
            return false;
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            $this->logger->error(sprintf('FacebookSDKException - msg = %s', $e->getMessage()));
            return false;
        }

        $graphUser = $response->getGraphUser();

        // dump($graphUser);

        $gender = $graphUser->getField('gender');
        $firstName = $graphUser->getField('first_name');
        $lastName = $graphUser->getField('last_name');
        $fbLink = $graphUser->getField('link');
        $birthday = $graphUser->getField('birthday');
        $about = $graphUser->getField('about');
        $bio = $graphUser->getField('bio');
        $address = $graphUser->getField('address');
        $location = $graphUser->getField('location');
        $website = $graphUser->getField('website');
        $isVerified = $graphUser->getField('is_verified');

        if (null !== $gender) {
            if ('male' === $gender) {
                $user->setGender('Monsieur');
            } elseif ('female' === $gender) {
                $user->setGender('Madame');
            }
        }

        if (null !== $firstName) {
            $user->setFirstName($firstName);
        }

        if (null !== $lastName) {
            $user->setName($lastName);
        }

        if (null !== $fbLink) {
            $user->setFacebook($fbLink);
        }

        if (null !== $birthday) {
            $user->setBirthday($birthday);
        }

        if (null !== $about) {
            $user->setSubtitle($about);
        }

        if (null !== $bio) {
            $user->setBiography($bio);
        }

        if (null !== $website) {
            $user->setWebsite($website);
        }

        if (null !== $isVerified) {
            $user->setValidated($isVerified);
        }

        return true;
    }

    /**
     * Retrieve Twitter API data to update user object.
     * https://dev.twitter.com/rest/reference/get/users/show
     *
     * @param integer $providerId
     * @param string $accessToken
     * @param string $tokenSecret
     * @param PUser $user
     * @return boolean
     */
    private function manageTwitterApiExtraData($providerId, $accessToken, $tokenSecret, PUser $user)
    {
        // https://api.twitter.com/1.1/users/show.json?screen_name=lionelbzv

        $url = 'https://api.twitter.com/1.1/users/show.json';
        $getfield = sprintf('?user_id=%s', $providerId);
        $requestMethod = 'GET';

        $settings = array(
            'oauth_access_token' => $accessToken,
            'oauth_access_token_secret' => $tokenSecret,
            'consumer_key' => $this->twitterApiKey,
            'consumer_secret' => $this->twitterApiSecret
        );

        try {
            $twitterClient = new TwitterAPIExchange($settings);
            $twitterResult =  $twitterClient->setGetfield($getfield)
                ->buildOauth($url, $requestMethod)
                ->performRequest();
            $twitterResult = json_decode($twitterResult);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('Exception - msg = %s', $e->getMessage()));
            return false;
        }

        // dump($twitterResult);

        if (isset($twitterResult->error)) {
            return false;
        }

        if (isset($twitterResult->screen_name) && null !== $twitterResult->screen_name) {
            $user->setTwitter(sprintf('https://twitter.com/%s', $twitterResult->screen_name));
        }

        if (isset($twitterResult->name) && null !== $twitterResult->name) {
            $names = explode(' ', $twitterResult->name);
            if (isset($names[0])) {
                $user->setFirstName($names[0]);
            }
            if (isset($names[1])) {
                $user->setName($names[1]);
            }
        }

        if (isset($twitterResult->description) && null !== $twitterResult->description) {
            $user->setBiography($twitterResult->description);
        }

        if (isset($twitterResult->url) && null !== $twitterResult->url) {
            $user->setWebsite($twitterResult->url);
        }

        if (isset($twitterResult->verified) && null !== $twitterResult->verified) {
            $user->setValidated($twitterResult->verified);
        }

        return true;
    }

    /**
     * Retrieve Google API data to update user object.
     * https://github.com/google/google-api-php-client
     * https://developers.google.com/api-client-library/php/
     * https://developers.google.com/+/api/latest/people/get
     *
     * @param integer $providerId
     * @param string $accessToken
     * @param string $refreshToken
     * @param string $expiresIn
     * @param PUser $user
     * @return boolean
     */
    private function manageGoogleApiExtraData($providerId, $accessToken, $refreshToken, $expiresIn, $user)
    {
        $client = new Google_Client();

        $client->setClientId($this->googleClientId);
        $client->setClientSecret($this->googleClientSecret);
        $client->setAccessToken(json_encode([
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'expires_in' => $expiresIn,
                'created' => time()
        ]));

        try {
            $googlePlus = new Google_Service_Oauth2($client);
            $googleResult = $googlePlus->userinfo->get();
            // dump($googleResult);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('Exception - msg = %s', $e->getMessage()));
            return false;
        }

        if (isset($googleResult->gender) && null !== $googleResult->gender) {
            if ('male' === $googleResult->gender) {
                $user->setGender('Monsieur');
            } elseif ('female' === $googleResult->gender) {
                $user->setGender('Madame');
            }
        }

        if (isset($googleResult->familyName) && null !== $googleResult->familyName) {
            $user->setName($googleResult->familyName);
        }

        if (isset($googleResult->givenName) && null !== $googleResult->givenName) {
            $user->setFirstName($googleResult->givenName);
        }

        // @todo bio, website

        return true;

    }

    /**
     * Retrieve an existing user w. provided oauth data
     *
     * @param $oAuthData
     * @return PUser
     */
    private function getUserFromOAuthData($oAuthData)
    {
        $this->logger->info('*** getUserFromOAuthData');

        if (!$oAuthData
            || !is_array($oAuthData)
            || !isset($oAuthData['provider'])
            || !isset($oAuthData['providerId'])
            ) {
            // unexpected oauth data, back to homepage
            throw new InconsistentDataException('Unexpected OAuth data');
        }

        // get db user
        $user = PUserQuery::create()
            ->filterByProvider($oAuthData['provider'])
            ->filterByProviderId($oAuthData['providerId'])
            ->findOne();

        // user previously connected w. classic id / pwd
        if (!$user && isset($oAuthData['email'])) {
            // get db user
            $user = PUserQuery::create()
                ->filterByEmail($oAuthData['email'])
                ->_or()
                ->filterByUsername($oAuthData['email'])
                ->findOne();
        }

        if ($user) {
            // update open id connection infos
            $user->setProvider($oAuthData['provider']);
            $user->setProviderId($oAuthData['providerId']);

            // update confirmation token
            if (isset($oAuthData['accessToken'])) {
                $user->setConfirmationToken($oAuthData['accessToken']);
            }

            // save user
            $user->save();
        }

        return $user;
    }

    /**
     * Retrieve an existing user w. provided oauth data
     *
     * @param array $oAuthData
     * @param boolean $isQualified citizen / elected
     * @return PUser
     */
    private function createUserFromOAuthData($oAuthData, $isQualified = false)
    {
        $this->logger->info('*** createUserFromOAuthData');

        // create new user & update it
        $user = new PUser();

        $user->setPUStatusId(UserConstants::STATUS_ACTIVED);
        $user->setQualified(false);
        $user->setOnline(false);

        $user = $this->userManager->updateOAuthData($user, $oAuthData);

        // manage download photo profile
        $this->manageOAuthProfilePhoto($user, $oAuthData['profilePicture']);

        // manage username / default email
        if ($user->getEmail()) {
            $username = $user->getEmail();
        } elseif ($user->getNickname()) {
            $username = $user->getNickname();
        } else {
            throw new InconsistentDataException('No email or nickname found in OAuth data, cannot create app profile.');
        }
        $user->setUsername($username);

        // fb / tw / g+ api connections to retrieve:
        //  - user's description > biography
        //  - users's coord > website, facebook, twitter, phone
        //  - user's profile verified attribute > validated
        $provider = $user->getProvider();
        $providerId = $user->getProviderId();
        $accessToken = $oAuthData['accessToken'];
        $tokenSecret = $oAuthData['tokenSecret'];
        $refreshToken = $oAuthData['refreshToken'];
        $expiresIn = $oAuthData['expiresIn'];

        switch ($provider) {
            case 'facebook':
                $this->manageFacebookApiExtraData($providerId, $accessToken, $user);
                break;
            case 'twitter':
                $this->manageTwitterApiExtraData($providerId, $accessToken, $tokenSecret, $user);
                break;
            case 'google':
                // https://github.com/hwi/HWIOAuthBundle/issues/833
                $this->manageGoogleApiExtraData($providerId, $accessToken, $refreshToken, $expiresIn, $user);
                break;
            default:
                throw new InconsistentDataException(sprintf('OAuth Provider %s not managed.'), $provider);
        }

        // save user
        $user->save();

        return $user;
    }

    /* ######################################################################################################## */
    /*                                              CONNECTION                                                  */
    /* ######################################################################################################## */

    /**
     * Connect & compute the redirect url
     *
     * @param PUser $user
     * @return string Redirect URL
     */
    public function connectUser($user)
    {
        // connect user
        $this->doPublicConnection($user);

        // redirect user
        $redirectUrl = $this->computeRedirectUrl($user);

        return $redirectUrl;
    }

    /**
     * OAuth connection or inscription
     * @param boolean $isQualified citizen / elected
     */
    public function oauthRegister($isQualified = false)
    {
        $this->logger->info('*** oauthRegister');

        // get oAuth data
        $oAuthData = $this->session->getFlashBag()->get('oAuthData');

        $user = $this->getUserFromOAuthData($oAuthData);

        // connect and redirect existing user
        if ($user) {
            return $this->connectUser($user);
        }

        // create new user
        $user = $this->createUserFromOAuthData($oAuthData, $isQualified);

        // update user for inscription process
        $roles = [ 'ROLE_CITIZEN_INSCRIPTION' ];
        if ($isQualified) {
            $roles = [ 'ROLE_ELECTED_INSCRIPTION' ];
        }

        // @todo to refactor
        $canonicalizer = $this->usernameCanonicalizer;
        if (null !== $user->getEmail()) {
            $canonicalizer = $this->emailCanonicalizer;
        }

        $user = $this->userManager->updateForInscriptionStart(
            $user,
            $roles,
            $canonicalizer->canonicalize($user->getUsername()),
            null
        );
        $user->save();

        // if all mandatory params are provided by oauth, connect else redirect to form step
        if (! $isQualified
            && null !== $user->getGender()
            && null !== $user->getFirstName()
            && null !== $user->getName()
            && null !== $user->getEmail()
        ) {
            $this->inscriptionCitizenFinish($user);
            $redirectUrl = $this->connectUser($user);
            return $redirectUrl;
        }

        $this->doPublicConnection($user);

        if ($isQualified) {
            return $this->router->generate('InscriptionElectedContact');
        }
        return $this->router->generate('InscriptionContact');
    }
    
    /* ######################################################################################################## */
    /*                                              INSCRIPTION                                                 */
    /* ######################################################################################################## */

    /**
     * Citizen inscription process start
     *
     * @param  PUser $user
     */
    public function inscriptionCitizenStart(PUser $user)
    {
        $this->logger->info('*** inscriptionCitizenStart');

        // citizen inscription roles
        $roles = [ 'ROLE_CITIZEN_INSCRIPTION' ];

        // update user
        $this->userManager->updateCanonicalFields($user);

        $user = $this->userManager->updateForInscriptionStart(
            $user,
            $roles,
            $this->usernameCanonicalizer->canonicalize($user->getEmail()),
            $this->encoderFactory->getEncoder($user)->encodePassword($user->getPlainPassword(), $user->getSalt())
        );

        // save user
        $user->save();

        // connect user
        $this->doPublicConnection($user);
    }

    /**
     * Citizen inscription process finish
     *
     * @param PUser $user
     */
    public function inscriptionCitizenFinish(PUser $user)
    {
        $this->logger->info('*** inscriptionCitizenFinish');

        // citizen inscription roles
        $roles = [ 'ROLE_CITIZEN', 'ROLE_PROFILE_COMPLETED' ];

        // update user
        $user = $this->userManager->updateForInscriptionFinish($user, $roles, UserConstants::STATUS_ACTIVED, false);
        
        // save user
        $user->save();

        // update reputation
        $user->updateReputation(ReputationConstants::ACTION_CITIZEN_INSCRIPTION);

        // (re)connect user
        $this->doPublicConnection($user);
    }

    /**
     * Elected inscription process start
     *
     * @param PUser $user
     */
    public function inscriptionElectedStart(PUser $user)
    {
        $this->logger->info('*** inscriptionElectedStart');
        
        // elected inscription roles
        $roles = [ 'ROLE_ELECTED_INSCRIPTION' ];

        // update user
        $user = $this->userManager->updateForInscriptionStart(
            $user,
            $roles,
            $this->usernameCanonicalizer->canonicalize($user->getUsername()),
            $this->encoderFactory->getEncoder($user)->encodePassword($user->getPlainPassword(), $user->getSalt())
        );

        // save user
        $user->save();

        // connect user
        $this->doPublicConnection($user);
    }


    /**
     * Citizen to elected migration process start
     *
     * @param  PUser $user
     */
    public function migrationElectedStart(PUser $user)
    {
        $this->logger->info('*** migrationElectedStart');

        // update role
        $user->addRole('ROLE_ELECTED_INSCRIPTION');
        $user->save();

        // connect user
        $this->doPublicConnection($user);
    }


    /**
     * Order payment completed
     */
    public function updateOrderPaymentCompleted()
    {
        $this->logger->info('*** updateOrderPaymentCompleted');

        // Session arguments
        $orderId = $this->session->get('p_order_id');

        // get order
        $order = POrderQuery::create()->findPk($orderId);
        if (!$order) {
            throw new \InconsistentDataException(sprintf('Order %s not found', $orderId));
        }

        // update order & payment states
        switch($order->getPOPaymentTypeId()) {
            case OrderConstants::PAYMENT_TYPE_BANK_TRANSFER:
            case OrderConstants::PAYMENT_TYPE_CHECK:
                $order->setPOOrderStateId(OrderConstants::ORDER_WAITING);
                $order->setPOPaymentStateId(OrderConstants::PAYMENT_WAITING);
                $order->save();

                $this->eventDispatcher->dispatch('order_email', new GenericEvent($order));

                break;
            case OrderConstants::PAYMENT_TYPE_CREDIT_CARD:
            case OrderConstants::PAYMENT_TYPE_PAYPAL:
                // management via asynchronous apis response
                break;
            default:
                throw new InconsistentDataException(sprintf('Order payment type id %s not managed.'), $order->getPOPaymentTypeId());
        }
    }

    /**
     * Order canceled
     */
    public function updateOrderPaymentCanceled()
    {
        $this->logger->info('*** updateOrderPaymentCanceled');

        // Session arguments
        $orderId = $this->session->get('p_order_id');

        // get order
        $order = POrderQuery::create()->findPk($orderId);
        if (!$order) {
            throw new \InconsistentDataException(sprintf('Order %s not found', $orderId));
        }

        $this->orderManager->deleteOrder($order);
    }

    /**
     *  Finalisation du process d'inscription élu
     *
     */
    public function inscriptionFinishElected(PUser $user)
    {
        $this->logger->info('*** inscriptionFinishElected');

        // citizen inscription roles
        $roles = [ 'ROLE_ELECTED', 'ROLE_CITIZEN' /* during waiting for validation */, 'ROLE_PROFILE_COMPLETED' ];

        // update user
        $user = $this->userManager->updateForInscriptionFinish(
            $user,
            $roles,
            UserConstants::STATUS_VALIDATION_PROCESS,
            true
        );

        // update reputation
        $user->updateReputation(ReputationConstants::ACTION_ELECTED_INSCRIPTION);

        // save user
        $user->save();
        
        $this->doPublicConnection($user);
    }
}
