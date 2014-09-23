<?php
namespace Politizr\FrontBundle\Tests\Twig;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Politizr\FrontBundle\Twig\PolitizrExtension;

use Politizr\Model\PUser;
use Politizr\Model\PDDebate;
use Politizr\Model\PUFollowDD;

/**
 *  Classe de test de PolitizrExtension
 *
 *
 *  @author     Lionel Bouzonville / Studio Echo
 */
class PolitizrExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     *  linkFollow
     */
    public function testLinkFollow()
    {        
//         $container = new ContainerBuilder();
// 
//         // $serviceContainer = 
//         $politizrExt = new PolitizrExtension($container);
// 
//         // creation de pUser follower
//         $pUserFollower1 = new PUser();
//         $pUserFollower2 = new PUser();
//         $pUserFollower3 = new PUser();
// 
//         // creation de pdDebate
//         $pdDebate1 = new PDDebate();
//         $pdDebate2 = new PDDebate();
//         $pdDebate3 = new PDDebate();
// 
//         // creation de pUser suivis
//         $pUser1 = new PUser();
//         $pUser2 = new PUser();
//         $pUser3 = new PUser();
// 
//         // pUserFollower1 suit pdDebate1 et pUser1
//         $puFollowDD = new PUFollowDD();
//         $puFollowDD->setUserId($pUserFollower1->getId());
//         $puFollowDD->setPDDebateId($pdDebate1->getId());
// 
//         // pUserFollower2 suit pdDebate2
// 
//         // pUserFollower3 suit pUser3



        // $this->assertEquals("test", $politizrExt->linkFollow($objectId, $objectType));
    }

}