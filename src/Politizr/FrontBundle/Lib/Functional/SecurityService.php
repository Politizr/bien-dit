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
     */
    private function doPublicConnection(PUser $user)
    {
        $providerKey = 'public';

        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
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
          'default_graph_version' => 'v2.4',
          'default_access_token' => $accessToken, // optional
        ]);

        try {
            // Get the Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.

            // ?fields=email,about,address,birthday,bio
            $response = $facebookClient->get(
                sprintf('/%s?fields=gender,first_name,last_name,birthday,about,bio,address,location,website,is_verified', $providerId)
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

        // @todo / how to get the facebook page url?

        $gender = $graphUser->getField('gender');
        $firstName = $graphUser->getField('first_name');
        $lastName = $graphUser->getField('last_name');
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
        https://api.twitter.com/1.1/users/show.json?screen_name=lionel09

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

        // @todo / how to get the twitter page url?

        // dump($twitterResult);
        if (isset($twitterResult->error)) {
            return false;
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
            $user->setSubtitle($twitterResult->description);
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
//            'refresh_token' => $refreshToken,
//            'expires_in' => $expiresIn,
        ]));

        // http://stackoverflow.com/questions/13506930/how-to-get-oauth2-access-token-with-google-api-php-client
        //$client->setAssertionCredentials(new Google_AssertionCredentials(
        //    SERVICE_ACCOUNT_NAME,
        //    array('https://www.googleapis.com/auth/fusiontables'),
        //    $key)
        //);

        // http://stackoverflow.com/questions/22117243/getting-user-info-google-php-client-issue
        // $googlePlus = new Google_Service_Plus($client);
        $googlePlus = new Google_Service_Oauth2($client);
        $userProfile = $googlePlus->userinfo->get();
        // var_dump($userProfile);
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
     */
    public function oauthRegister()
    {
        $this->logger->info('*** oauthRegister');

        // get user
        $user = $this->securityTokenStorage->getToken()->getUser();

        // get oAuth data
        $oAuthData = $this->session->getFlashBag()->get('oAuthData');
        if (!$oAuthData
            || !is_array($oAuthData)
            || !isset($oAuthData['provider'])
            || !isset($oAuthData['providerId'])
            ) {
            // unexpected oauth data, back to homepage
            return $this->router->generate('Homepage');
        }
        
        // get db user
        $user = PUserQuery::create()
            ->filterByProvider($oAuthData['provider'])
            ->filterByProviderId($oAuthData['providerId'])
            ->findOne();

        if ($user) {
            // update confirmation token
            if (isset($oAuthData['accessToken'])) {
                $user->setConfirmationToken($oAuthData['accessToken']);
            }

            // save user
            $user->save();

            // connect and redirect user
            $redirectUrl = $this->connectUser($user);

            return $redirectUrl;
        } else {
            // citizen inscription roles
            $roles = [ 'ROLE_CITIZEN_INSCRIPTION' ];

            // create new user & update it
            $user = new PUser();

            $user->setPUStatusId(UserConstants::STATUS_ACTIVED);
            $user->setQualified(false);
            $user->setOnline(false);

            $user = $this->userManager->updateOAuthData($user, $oAuthData);

            // manage download photo profile
            $this->manageOAuthProfilePhoto($user, $oAuthData['profilePicture']);

            // manage username
            if ($user->getNickname()) {
                $username = $user->getNickname();
                $canonicalizer = $this->usernameCanonicalizer;
            } elseif ($user->getEmail()) {
                $username = $user->getEmail();
                $canonicalizer = $this->emailCanonicalizer;
            } else {
                throw new InconsistentDataException('No email or nickname found in OAuth data, cannot create app profile.');
            }
            $user->setUsername($username);

            // manage canonicalization & roles
            $user = $this->userManager->updateForInscriptionStart(
                $user,
                $roles,
                $canonicalizer->canonicalize($username),
                null
            );

            // @todo api connections fb / tw / g+ to retrieve:
            //  - user's description > summary & biography
            //  - users's coord > website, facebook, twitter, phone
            //  - user's profile verified attribute > validated
            $provider = $user->getProvider();
            $providerId = $user->getProviderId();
            $accessToken = $oAuthData['accessToken'];
            $tokenSecret = $oAuthData['tokenSecret'];
            $refreshToken = $oAuthData['refreshToken'];
            $expiresIn = $oAuthData['expiresIn'];

            $this->logger->info('*** token & co');
            $this->logger->info(print_r($accessToken, true));
            $this->logger->info(print_r($tokenSecret, true));
            $this->logger->info(print_r($refreshToken, true));
            $this->logger->info(print_r($expiresIn, true));

            switch ($provider) {
                case 'facebook':
                    $this->manageFacebookApiExtraData($providerId, $accessToken, $user);
                    break;
                case 'twitter':
                    $this->manageTwitterApiExtraData($providerId, $accessToken, $tokenSecret, $user);
                    break;
                case 'google':
                    // https://github.com/hwi/HWIOAuthBundle/issues/833
                    // $this->manageGoogleApiExtraData($providerId, $accessToken, $refreshToken, $expiresIn, $user);
                    break;
                default:
                    throw new InconsistentDataException(sprintf('OAuth Provider %s not managed.'), $provider);
            }

            // save user
            $user->save();

            // connect user
            $this->doPublicConnection($user);

            // redirect to inscription next step
            return $this->router->generate('InscriptionContact');
        }
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
        $user = $this->userManager->updateForInscriptionFinish($user, $roles, UserConstants::STATUS_VALIDATION_PROCESS, true);

        // save user
        $user->save();
        
        $this->doPublicConnection($user);
    }
}
