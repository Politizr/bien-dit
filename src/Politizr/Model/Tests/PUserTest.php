<?php
namespace Politizr\Model\Tests\PUser;

use Politizr\Model\Tests\PolitizrTestBase;

use Politizr\Model\PUser;
use Politizr\Model\PUType;
use Politizr\Model\PUStatus;



/**
 * 	Classe de test de PUser
 *  DOC:
 *       http://symfony.com/fr/doc/2.3/book/testing.html
 *       http://propelorm.org/Propel/cookbook/symfony2/testing.html
 *       http://symfony.com/fr/doc/current/cookbook/testing/database.html
 *       https://phpunit.de/manual/4.2/en/writing-tests-for-phpunit.html
 *       http://keiruaprod.fr/symblog-fr/docs/tests-unitaires-et-fonctionels-phpunit.html
 *       + mockery
 *
 *  TODO:
 *		- test de l'upload
 *		- init / purge de la bdd?
 *
 *	@author 	Lionel Bouzonville / Studio Echo
 */
class PUserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * This is run before each unit test
     * TODO: it populates the database?
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * This is run after each unit test
     * TODO: it empties the database?
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

	/**
	 *	
	 */
    public function test__toString()
    {
    	$pUser = new PUser();

        $pUser->setFirstname('François');
        $pUser->setName('Hollande');
        $this->assertEquals('François Hollande', $pUser, 'PUser->__toString() doit renvoyer "prénom nom".');

        $pUser->setFirstname(null);
        $pUser->setName('Chirac');
        $this->assertEquals('Chirac', $pUser, 'PUser->__toString() doit renvoyer "nom" si pas de prénom.');

        $pUser->setFirstname('Nicolas');
        $pUser->setName(null);
        $this->assertEquals('Nicolas', $pUser, 'PUser->__toString() doit renvoyer "prénom" si pas de nom.');

        $pUser->setFirstname(null);
        $pUser->setName(null);
        $this->assertEquals('', $pUser, 'PUser->__toString() doit renvoyer "" si pas de prénom ni de nom.');
    }

    /**
     *
     */
    public function testGetBirthdayText() {
    	$pUser = new PUser();

    	$pUser->setBirthday(new \DateTime('1976-12-04'));

    	$this->assertEquals('04/12/1976', $pUser->getBirthdayText(), 'PUser->getBirthdayText() doit renvoyer au format "dd/mm/YYYY".');
    }

    /**
     *
     */
    public function testCreateRawSlug() {
    	$pUser = new PUser();

    	$pUser->setFirstname('Frédéric');
    	$pUser->setName('Aàèéùçi');

    	$this->assertEquals('frederic-aaeeuci', $pUser->createRawSlug(), 'PUser->createRawSlug() doit remplacer les accents par leur caractère non accentué équivalent.');
    }

    /**
     *
     */
    public function testSerializeUnserialize() {
    	$pUser = new PUser();

		$pUser->setId(1);
		$pUser->setPUTypeId(PUType::TYPE_CITOYEN);
		$pUser->setPUStatusId(PUStatus::ACTIVED);
		$pUser->setUsername("françoish");
		$pUser->setName("Hollande");
		$pUser->setFirstname("François");
		$pUser->setBirthday(new \DateTime('1976-12-04'));
		$pUser->setSalt("s@lt");
		$pUser->setPassword("p@ssword");
		$pUser->setExpired(false);
		$pUser->setLocked(false);
		$pUser->setCredentialsExpired(false);
		$pUser->setEnabled(true);

		$serialized = $pUser->serialize();

		$pUser = new PUser();
		$pUser->unserialize($serialized);

		$this->assertEquals(1, $pUser->getId(), 'PUser seralize / deserialize ne doit pas perdre de valeur.');
		$this->assertEquals(PUType::TYPE_CITOYEN, $pUser->getPUTypeId(), 'PUser seralize / deserialize ne doit pas perdre de valeur.');
		$this->assertEquals(PUStatus::ACTIVED, $pUser->getPUStatusId(), 'PUser seralize / deserialize ne doit pas perdre de valeur.');
		$this->assertEquals("françoish", $pUser->getUsername(), 'PUser seralize / deserialize ne doit pas perdre de valeur.');
		$this->assertEquals("Hollande", $pUser->getName(), 'PUser seralize / deserialize ne doit pas perdre de valeur.');
		$this->assertEquals("François", $pUser->getFirstname(), 'PUser seralize / deserialize ne doit pas perdre de valeur.');
		$this->assertEquals(new \DateTime('1976-12-04'), $pUser->getBirthday(), 'PUser seralize / deserialize ne doit pas perdre de valeur.');
		$this->assertEquals("s@lt", $pUser->getSalt(), 'PUser seralize / deserialize ne doit pas perdre de valeur.');
		$this->assertEquals("p@ssword", $pUser->getPassword(), 'PUser seralize / deserialize ne doit pas perdre de valeur.');
		$this->assertEquals(false, $pUser->getExpired(), 'PUser seralize / deserialize ne doit pas perdre de valeur.');
		$this->assertEquals(false, $pUser->getLocked(), 'PUser seralize / deserialize ne doit pas perdre de valeur.');
		$this->assertEquals(false, $pUser->getCredentialsExpired(), 'PUser seralize / deserialize ne doit pas perdre de valeur.');
		$this->assertEquals(true, $pUser->getEnabled(), 'PUser seralize / deserialize ne doit pas perdre de valeur.');
    }

}