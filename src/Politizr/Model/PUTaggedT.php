<?php

namespace Politizr\Model;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Politizr\Model\om\BasePUTaggedT;

/**
 *
 * @author Lionel Bouzonville
 */
class PUTaggedT extends BasePUTaggedT implements ContainerAwareInterface
{
    // elastica search
    private $elasticaPersister;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        if ($container) {
            $this->elasticaPersister = $container->get('fos_elastica.object_persister.politizr.p_user');
        }
    }

    /**
     * @todo: gestion d'une exception spécifique à ES
     *
     */
    public function postInsert(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            // get associated object
            $user = PUserQuery::create()->findPk($this->getPUserId());
            if ($user && $user->isIndexable()) {
                // $this->elasticaPersister->replaceOne($user);
            }
        } else {
            throw new \Exception('Indexation service not found');
        }
    }

    /**
     * @todo: gestion d'une exception spécifique à ES
     *
     */
    public function postUpdate(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            // get associated object
            $user = PUserQuery::create()->findPk($this->getPUserId());
            if ($user && $user->isIndexable()) {
                // $this->elasticaPersister->replaceOne($user);
            }
        } else {
            throw new \Exception('Indexation service not found');
        }
    }

    /**
     * @todo: gestion d'une exception spécifique à ES
     *
     */
    public function postDelete(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            // get associated object
            $user = PUserQuery::create()->findPk($this->getPUserId());
            if ($user && $user->isIndexable()) {
                // $this->elasticaPersister->replaceOne($user);
            }
        } else {
            throw new \Exception('Indexation service not found');
        }
    }
}
