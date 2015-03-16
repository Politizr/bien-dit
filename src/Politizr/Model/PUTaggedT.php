<?php

namespace Politizr\Model;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Politizr\Model\om\BasePUTaggedT;

class PUTaggedT extends BasePUTaggedT implements ContainerAwareInterface
{
    // *****************************  ELASTIC SEARCH  ****************** //
       private $elasticaPersister;

       /**
        *
        */
    public function setContainer(ContainerInterface $container = null) {
        if($container) $this->elasticaPersister = $container->get('fos_elastica.object_persister.politizr.p_user');
    }

    /**
     * TODO: gestion d'une exception spécifique à ES
     *
     */
    public function postInsert(\PropelPDO $con = null) {
        if ($this->elasticaPersister) {
            // Récupération de l'objet indexé associé
            $user = PUserQuery::create()->findPk($this->getPUserId());
            if ($user && $user->isIndexable()) {
                $this->elasticaPersister->replaceOne($user);
            }
        } else {
            throw new \Exception('Service d\'indexation non dispo');
        }
    }

    /**
     * TODO: gestion d'une exception spécifique à ES
     *
     */
    public function postUpdate(\PropelPDO $con = null) {
        if ($this->elasticaPersister) {
            // Récupération de l'objet indexé associé
            $user = PUserQuery::create()->findPk($this->getPUserId());
            if ($user && $user->isIndexable()) {
                $this->elasticaPersister->replaceOne($user);
            }
        } else {
            throw new \Exception('Service d\'indexation non dispo');
        }
    }

    /**
     * TODO: gestion d'une exception spécifique à ES
     *
     */
    public function postDelete(\PropelPDO $con = null) {
        if ($this->elasticaPersister) {
            // Récupération de l'objet indexé associé
            $user = PUserQuery::create()->findPk($this->getPUserId());
            if ($user && $user->isIndexable()) {
                $this->elasticaPersister->replaceOne($user);
            }
        } else {
            throw new \Exception('Service d\'indexation non dispo');
        }
    }

}
