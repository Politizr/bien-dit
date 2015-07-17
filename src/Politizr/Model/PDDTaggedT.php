<?php

namespace Politizr\Model;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Politizr\Model\om\BasePDDTaggedT;

/**
 * Relation debate <-> tag
 *
 * @author Lionel Bouzonville
 */
class PDDTaggedT extends BasePDDTaggedT implements ContainerAwareInterface
{
    private $elasticaPersister;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        if ($container) {
            $this->elasticaPersister = $container->get('fos_elastica.object_persister.politizr.p_d_debate');
        }
    }

    /**
     * @todo: gestion d'une exception spécifique à ES
     */
    public function postInsert(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            $debate = PDDebateQuery::create()->findPk($this->getPDDebateId());
            if ($debate && $debate->isIndexable()) {
                $this->elasticaPersister->replaceOne($debate);
            }
        } else {
            throw new \Exception('Indexation service not found');
        }
    }

    /**
     * @todo: gestion d'une exception spécifique à ES
     */
    public function postUpdate(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            $debate = PDDebateQuery::create()->findPk($this->getPDDebateId());
            if ($debate && $debate->isIndexable()) {
                $this->elasticaPersister->replaceOne($debate);
            }
        } else {
            throw new \Exception('Indexation service not found');
        }
    }

    /**
     * @todo: gestion d'une exception spécifique à ES
     */
    public function postDelete(\PropelPDO $con = null)
    {
        if ($this->elasticaPersister) {
            $debate = PDDebateQuery::create()->findPk($this->getPDDebateId());
            if ($debate && $debate->isIndexable()) {
                $this->elasticaPersister->replaceOne($debate);
            }
        } else {
            throw new \Exception('Indexation service not found');
        }
    }
}
