<?php
namespace Politizr\FrontBundle\Lib\Client;

use Politizr\Exception\InconsistentDataException;

/**
 * SOAP Client for Ariad WSDL
 * beta
 *
 * @author Lionel Bouzonville
 */
class Ariad
{
    public $id;
    public $passwd;

    public function __construct($id, $passwd)
    {
        $this->id = $id;
        $this->passwd = $passwd;
    }
}
