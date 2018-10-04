<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PDocumentInterface;

use Facebook;
use Facebook\FacebookApp;
use Facebook\FacebookRequest;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;



/**
 * Functional service for facebook management.
 * cf https://developers.facebook.com/docs/graph-api/reference/v2.8/insights/
 *
 * @author Lionel Bouzonville
 */
class FacebookService
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;
    
    private $fbAppId;
    private $fbAppSecret;
    private $fbPageId;
    private $fbGraphVersion;
    private $fbAccessToken;

    private $router;

    private $globalTools;
    
    private $logger;

    private $facebook;
    private $facebookClient;
    private $facebookApp;


    /**
     * Get current user id
     *
     * @return int
     */
    private function getCurrentUserId()
    {
        $user = $this->securityTokenStorage->getToken()->getUser();

        if ($user) {
            return $user->getId();
        }

        return null;
    }

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * "%facebook_client_id%"
     * "%facebook_client_secret%"
     * "%facebook_page_id%"
     * "%facebook_graph_version%"
     * "%facebook_access_token%"
     * @param @router
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $fbAppId,
        $fbAppSecret,
        $fbPageId,
        $fbGraphVersion,
        $fbAccessToken,
        $router,
        $globalTools,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->fbAppId = $fbAppId;
        $this->fbAppSecret = $fbAppSecret;
        $this->fbPageId = $fbPageId;
        $this->fbGraphVersion = $fbGraphVersion;
        $this->fbAccessToken = $fbAccessToken;

        $this->router = $router;

        $this->globalTools = $globalTools;

        $this->logger = $logger;

        // Init Facebook instance
        $this->facebook = new Facebook\Facebook([
          'app_id' => $this->fbAppId,
          'app_secret' => $this->fbAppSecret,
          'default_graph_version' => $this->fbGraphVersion,
          'default_access_token' => $this->fbAccessToken,
        ]);

        $this->facebookClient = $this->facebook->getClient();
        $this->facebookApp = $this->facebook->getApp();
    }

    /**
     * Get the number of impressions for your Page post
     *
     * @param string $fbAdId
     * @return int
     */
    public function getImpressions($fbAdId)
    {
        $impressions = null;

        try {
            // Impressions > The number of impressions for your Page post
            $request = new FacebookRequest(
                $this->facebookApp,
                $this->facebook->getDefaultAccessToken(),
                'GET',
                $this->fbPageId . '_' . $fbAdId . '/insights/post_impressions_unique',
                array()
            );
            $response = $this->facebookClient->sendRequest($request);
            $graphEdge = $response->getGraphEdge();
            foreach ($graphEdge as $graphNode) {
                foreach ($graphNode->getField('values') as $node) {
                    $impressions = $node->getField('value');
                }
            }
        } catch (FacebookResponseException $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            // When Graph returns an error
            throw new InconsistentDataException(sprintf('FacebookResponseException - msg = %s', $e->getMessage()));
        } catch (FacebookSDKException $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            // When validation fails or other local issues
            throw new InconsistentDataException(sprintf('FacebookSDKException - msg = %s', $e->getMessage()));
        } catch (\Exception $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            throw new InconsistentDataException(sprintf('Exception - msg = %s', $e->getMessage()));
        }

        return $impressions;
    }

    /**
     * Get the number of interactions for your Page post
     *
     * @param string $fbAdId
     * @return int
     */
    public function getInteractions($fbAdId)
    {
        $interactions = null;

        try {
            // Interactions > The number of times people clicked on anywhere in your posts without generating a story
            $request = new FacebookRequest(
                $this->facebookApp,
                $this->facebook->getDefaultAccessToken(),
                'GET',
                $this->fbPageId . '_' . $fbAdId . '/insights/post_clicks',
                array()
            );
            $response = $this->facebookClient->sendRequest($request);
            $graphEdge = $response->getGraphEdge();
            foreach ($graphEdge as $graphNode) {
                foreach ($graphNode->getField('values') as $node) {
                    $interactions = $node->getField('value');
                }
            }
        } catch (FacebookResponseException $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            // When Graph returns an error
            throw new InconsistentDataException(sprintf('FacebookResponseException - msg = %s', $e->getMessage()));
        } catch (FacebookSDKException $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            // When validation fails or other local issues
            throw new InconsistentDataException(sprintf('FacebookSDKException - msg = %s', $e->getMessage()));
        } catch (\Exception $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            throw new InconsistentDataException(sprintf('Exception - msg = %s', $e->getMessage()));
        }

        return $interactions;
    }

    /**
     * Get the number of emotions for your Page post
     *
     * @param string $fbAdId
     * @return int
     */
    public function getEmotions($fbAdId)
    {
        $emotions = null;

        try {
            // Emotions > Likes
            $request = new FacebookRequest(
                $this->facebookApp,
                $this->facebook->getDefaultAccessToken(),
                'GET',
                $this->fbPageId . '_' . $fbAdId . '/insights/post_reactions_by_type_total',
                array()
            );
            $response = $this->facebookClient->sendRequest($request);
            $graphEdge = $response->getGraphEdge();
            foreach ($graphEdge as $graphNode) {
                foreach ($graphNode->getField('values') as $node) {
                    $emotions = $node->getField('value')->asArray();
                }
            }
        } catch (FacebookResponseException $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            // When Graph returns an error
            throw new InconsistentDataException(sprintf('FacebookResponseException - msg = %s', $e->getMessage()));
        } catch (FacebookSDKException $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            // When validation fails or other local issues
            throw new InconsistentDataException(sprintf('FacebookSDKException - msg = %s', $e->getMessage()));
        } catch (\Exception $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            throw new InconsistentDataException(sprintf('Exception - msg = %s', $e->getMessage()));
        }

        return $emotions;
    }

    /**
     * Get the number of emotions for your Page post
     *
     * @param string $fbAdId
     * @return int
     */
    public function getNbEmotions($fbAdId)
    {
        $emotions = $this->getEmotions($fbAdId);

        $nbEmotions = 0;
        foreach ($emotions as $emotion) {
            $nbEmotions += $emotion;
        }

        return $nbEmotions;
    }

    /**
     * Get the number of comments for your Page post
     *
     * @param string $fbAdId
     * @return array
     */
    public function getNbComments($fbAdId)
    {
        $nbComments = '';

        try {
            // Comments > Comments
            $request = new FacebookRequest(
                $this->facebookApp,
                $this->facebook->getDefaultAccessToken(),
                'GET',
                $this->fbPageId . '_' . $fbAdId . '/comments',
                array(
                    'order' => 'reverse_chronological',
                    'summary' => true,
                    'limit' => 5,
                )
            );
            $response = $this->facebookClient->sendRequest($request);
            $graphEdge = $response->getGraphEdge();
            if ($graphEdge->getMetaData() && isset($graphEdge->getMetaData()['summary']) && isset($graphEdge->getMetaData()['summary']['total_count'])) {
                $nbComments = $graphEdge->getMetaData()['summary']['total_count'];
            }
        } catch (FacebookResponseException $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            // When Graph returns an error
            throw new InconsistentDataException(sprintf('FacebookResponseException - msg = %s', $e->getMessage()));
        } catch (FacebookSDKException $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            // When validation fails or other local issues
            throw new InconsistentDataException(sprintf('FacebookSDKException - msg = %s', $e->getMessage()));
        } catch (\Exception $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            throw new InconsistentDataException(sprintf('Exception - msg = %s', $e->getMessage()));
        }

        return $nbComments;
    }

    /**
     * Get the number of comments for your Page post
     *
     * @param string $fbAdId
     * @return array
     */
    public function getNbShares($fbAdId)
    {
        $nbShares = '';

        try {
            // Comments > Comments
            $request = new FacebookRequest(
                $this->facebookApp,
                $this->facebook->getDefaultAccessToken(),
                'GET',
                $this->fbPageId . '_' . $fbAdId . '?fields=shares',
                array()
            );
            $response = $this->facebookClient->sendRequest($request);
            $data = $response->getDecodedBody();
            if ($data && isset($data['shares']) && isset($data['shares']['count'])) {
                $nbShares = $data['shares']['count'];
            }
        } catch (FacebookResponseException $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            throw new InconsistentDataException(sprintf('FacebookResponseException - msg = %s', $e->getMessage()));
        } catch (FacebookSDKException $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            // When validation fails or other local issues
            throw new InconsistentDataException(sprintf('FacebookSDKException - msg = %s', $e->getMessage()));
        } catch (\Exception $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            throw new InconsistentDataException(sprintf('Exception - msg = %s', $e->getMessage()));
        }

        return $nbShares;
    }

    /**
     * Get the number of comments for your Page post
     *
     * @param string $fbAdId
     * @return array
     */
    public function getComments($fbAdId)
    {
        $comments = array();

        try {
            // Comments > Comments
            $request = new FacebookRequest(
                $this->facebookApp,
                $this->facebook->getDefaultAccessToken(),
                'GET',
                $this->fbPageId . '_' . $fbAdId . '/comments',
                array(
                    'order' => 'reverse_chronological',
                    'summary' => true,
                    'limit' => 5,
                )
            );
            $response = $this->facebookClient->sendRequest($request);
            $graphEdge = $response->getGraphEdge();
            foreach ($graphEdge as $node) {
                $fromNode = $node->getField('from');

                if ($fromNode) {
                    $fbUserId = $fromNode->getField('id');
                    $name = $fromNode->getField('name');
                    $picture = $this->getUserPicture($fbUserId);
                    $fbAuthor = [ 'name' => $name, 'picture' => $picture ];
                } else {
                    $fbAuthor = [ 'name' => 'undefined', 'picture' => null ];
                }

                $comments[] = ['author' => $fbAuthor, 'message' => $node->getField('message')];
            }
        } catch (FacebookResponseException $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            // When Graph returns an error
            throw new InconsistentDataException(sprintf('FacebookResponseException - msg = %s', $e->getMessage()));
        } catch (FacebookSDKException $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            // When validation fails or other local issues
            throw new InconsistentDataException(sprintf('FacebookSDKException - msg = %s', $e->getMessage()));
        } catch (\Exception $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            throw new InconsistentDataException(sprintf('Exception - msg = %s', $e->getMessage()));
        }

        return $comments;
    }

    /**
     *
     */
    public function getUserPicture($fbUserId)
    {
        $picture = null;

        try {
            // Comments > Comments
            $request = new FacebookRequest(
                $this->facebookApp,
                $this->facebook->getDefaultAccessToken(),
                'GET',
                $fbUserId . '/picture',
                array(
                    'redirect' => false
                )
            );
            $response = $this->facebookClient->sendRequest($request);
            $data = $response->getDecodedBody();
            if ($data && isset($data['data']) && isset($data['data']['url'])) {
                $picture = $data['data']['url'];
            }
        } catch (FacebookResponseException $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            // When Graph returns an error
            throw new InconsistentDataException(sprintf('FacebookResponseException - msg = %s', $e->getMessage()));
        } catch (FacebookSDKException $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            // When validation fails or other local issues
            throw new InconsistentDataException(sprintf('FacebookSDKException - msg = %s', $e->getMessage()));
        } catch (\Exception $e) {
            $this->logger->error('Exception FB - msg = '.print_r($e->getMessage(), true));
            throw new InconsistentDataException(sprintf('Exception - msg = %s', $e->getMessage()));
        }

        return $picture;
    }
}