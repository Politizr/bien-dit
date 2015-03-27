<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUserQuery;

class PUserQuery extends BasePUserQuery
{


    // *****************************    AGGREGATIONS / UTILES    ************************* //
    
    /**
     * Cumule les contraintes associés à un objet en ligne
     *
     * @return  Query
     */
    public function online()
    {
        return $this->filterByOnline(true)->filterByPUStatusId(PUStatus::ACTIVED);
    }

    /**
     * Ordonne suivant un mot clef défini sur la vue.
     *
     * @param $keyword      string      Mot clef pour l'ordonnancement issu du html
     * @return  Query
     */
    public function orderWithKeyword($keyword = 'last')
    {
        return $this->_if('mostFollowed' === $keyword)
                        ->orderByMostFollowed()
                    ->_elseif('last' === $keyword)
                        ->orderByLast()
                    ->_endif();
    }

    /**
     * Ordonne les objets par meilleur note
     *
     * @return  Query
     */
    public function orderByNote()
    {
        return $this->orderByNotePos(\Criteria::DESC);
    }


    /**
     * Ordonne les objets par nombre de followers
     *
     * @return  Query
     */
    public function orderByMostFollowed()
    {
        return $this->joinPUFollowURelatedByPUserId('PUFollowU', \Criteria::LEFT_JOIN)
                ->withColumn('COUNT(PUFollowU.PUserId)', 'NbFollowers')
                ->groupBy('Id')
                ->orderBy('NbFollowers', \Criteria::DESC)
                ;
    }

    /**
     * Ordonne les objets par derniers créées
     *
     */
    public function orderByLast()
    {
        return $this->orderByCreatedAt(\Criteria::DESC);
    }

    /**
     * Filtre suivant le mot(s) clef(s) défini sur la vue
     *
     * @param array $keywords
     * @param \Politizr\Model\PUser $user
     * @return Query
     */
    public function filterByKeywords($keywords = null, \Politizr\Model\PUser $user = null)
    {
        return $this->_if($keywords && in_array('newest', $keywords))
                        ->filterByNewest()
                    ->_endif()
                    ->_if($keywords && in_array('qualified', $keywords))
                        ->filterByQualified(true)
                    ->_endif()
                    ->_if($keywords && in_array('suggestion', $keywords))
                        ->filterBySuggestion($user)
                    ->_endif()
                    ;
    }

    /**
     * Filtre les objets les plus récents
     *
     * @return  Query
     */
    public function filterByNewest()
    {
        // Dates début / fin
        $now = new \DateTime();
        $nowMin24 = new \DateTime();

        // -24h tant qu'il n'y a pas de résultats significatifs
        $nb = 0;
        while ($nb < 10) {
            $nb = PUserQuery::create()->online()->filterByCreatedAt(array('min' => $nowMin24, 'max' => $now))->count();
            $nowMin24->modify('-1 day');
        }

        return $this->filterByCreatedAt(array('min' => $nowMin24, 'max' => $now));
    }

    /**
     * Filtre les objets en fonction des tags suivis par le user entré en paramètre.
     *
     * @return  Query
     */
    public function filterBySuggestion(\Politizr\Model\PUser $user = null)
    {
        if (null === $user) {
            return $this;
        } else {
            // Récupère la liste des IDs des tags suivis
            $followedIds = PTagQuery::create()
                            ->select('Id')
                            ->filterByOnline(true)
                            ->filterByPuFollowTPUser($user)
                            ->find();

            // Récupère les débats
            $query = $this->usePuTaggedTPUserQuery()
                            ->filterByPTagId($followedIds->getData())
                        ->endUse()
                        // débats non suivis uniquement
                        ->where('p_user.id NOT IN (SELECT p_user_id FROM p_u_follow_u WHERE p_user_id = ?)', $user->getId())
                        ->filterById($user->getId(), \Criteria::NOT_EQUAL)
                        ->setDistinct()
                        ;
        
            return $query;
        }
    }

    /**
     * Filtre les objets par géolocalisation
     * TODO requête géoloc / tags
     *
     * @param   Geocoder\Result\Geocoded    $geocoded
     * @return  Query
     */
    public function filterByGeolocalization(Geocoded $geocoded)
    {
    }
}
