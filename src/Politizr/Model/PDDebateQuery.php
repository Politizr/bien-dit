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
     */
    public function online()
    {
        return $this->filterByOnline(true)->filterByPublished(true);
    }

    /**
     *    Ordonne les objets par meilleur note
     *
     *     @return  Query
     */
    public function bestNote()
    {
        return $this->orderByNotePos(\Criteria::DESC);
    }

    /**
     *    Ordonne les objets par nombre de followers
     *
     *     @return  Query
     */
    public function mostFollowed()
    {
        return $this->joinPuFollowDdPDDebate('PUFollowDD', \Criteria::LEFT_JOIN)
                ->withColumn('COUNT(PUFollowDD.PUserId)', 'NbFollowers')
                ->groupBy('Id')
                ->orderBy('NbFollowers', \Criteria::DESC)
                ;

    }

    /**
     *    Derniers débats publiés
     *
     */
    public function last()
    {
        return $this->orderByPublishedAt(\Criteria::DESC);
    }

    /**
     *    Filtre les objets par géolocalisation
     *  TODO requête géoloc / tags
     *
     * @param     $geocoded     Geocoder\Result\Geocoded
     */
    public function geolocalized(Geocoded $geocoded)
    {
    }

    /**
     * Ordonne suivant un mot clef défini sur la vue.
     *
     * @param $keyword      string      Mot clef pour l'ordonnancement issu du html
     */
    public function orderWithKeyword($keyword = 'last')
    {
        return $this->_if($keyword == 'mostFollowed')
                        ->mostFollowed()
                    ->_elseif($keyword == 'bestNote')
                        ->bestNote()
                    ->_elseif($keyword == 'last')
                        ->last()
                    ->_endif();
    }
}
