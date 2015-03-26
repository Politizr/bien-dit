<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDDebateQuery;

use Geocoder\Result\Geocoded;

class PDDebateQuery extends BasePDDebateQuery
{

    // *****************************    SURCHARGE / DOCUMENT    ************************* //
    

    // *****************************    AGGREGATIONS / UTILES    ************************* //
    
    /**
     * Cumule les contraintes associés à un objet en ligne
     *
     * @return  Query
     */
    public function online()
    {
        return $this->filterByOnline(true)->filterByPublished(true);
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
                    ->_elseif('bestNote' === $keyword)
                        ->orderByNote()
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
        return $this->joinPuFollowDdPDDebate('PUFollowDD', \Criteria::LEFT_JOIN)
                ->withColumn('COUNT(PUFollowDD.PUserId)', 'NbFollowers')
                ->groupBy('Id')
                ->orderBy('NbFollowers', \Criteria::DESC)
                ;

    }

    /**
     * Ordonne les objets par derniers débats publiés
     *
     */
    public function orderByLast()
    {
        return $this->orderByPublishedAt(\Criteria::DESC);
    }

    /**
     * Filtre suivant le mot(s) clef(s) défini sur la vue
     *
     * @param $keywords array of string
     * @return Query
     */
    public function filterByKeywords($keywords = null)
    {
        return $this->_if($keywords && in_array('newest', $keywords))
                        ->filterByNewest()
                    ->_endif()
                    ->_if($keywords && in_array('qualified', $keywords))
                        ->filterByQualified()
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
        $nowMin24->modify('-1 day');

        // -24h tant que moins de X résultats
        $nb = 0;
        while ($nb < 10) {
            $nb = PDDebateQuery::create()
                    ->online()
                    ->filterByPublishedAt(array('min' => $nowMin24, 'max' => $now))
                    ->count();
            $nowMin24->modify('-1 day');
        }

        return $this->filterByPublishedAt(array('min' => $nowMin24, 'max' => $now));

    }

    /**
     * Filtre les objets uniquement rédigés par des profils débatteurs
     *
     * @return  Query
     */
    public function filterByQualified()
    {

        return $this->usePUserQuery()
                        ->filterByQualified(true)
                    ->endUse()
                    ;

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
