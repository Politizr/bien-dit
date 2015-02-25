<?php

namespace Politizr\FrontBundle\Lib\ElasticSearch;

use FOS\ElasticaBundle\Transformer\ElasticaToModelTransformerInterface;
use FOS\ElasticaBundle\HybridResult;
use Elastica\Document;

/**
 * Adaptation de ElasticaToModelTransformerCollection aux objets PropelCollection
 *
 * @author Lionel Bouzonville
 */
class ElasticaToModelTransformerPropelCollection implements ElasticaToModelTransformerInterface
{
    /**
     * @var ElasticaToModelTransformerInterface[]
     */
    protected $transformers = array();

    public function __construct(array $transformers)
    {
        $this->transformers = $transformers;
    }

    public function getObjectClass()
    {
        return array_map(function (ElasticaToModelTransformerInterface $transformer) {
            return $transformer->getObjectClass();
        }, $this->transformers);
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifierField()
    {
        return array_map(function (ElasticaToModelTransformerInterface $transformer) {
            return $transformer->getIdentifierField();
        }, $this->transformers);
    }

    /**
     * @param Document[] $elasticaObjects
     * @return PropelCollection
     */
    public function transform(array $elasticaObjects)
    {
        $sorted = array();
        foreach ($elasticaObjects as $object) {
            $sorted[$object->getType()][] = $object;
        }

        $transformed = array();
        foreach ($sorted as $type => $objects) {
            $transformedObjects = $this->transformers[$type]->transform($objects);
            $identifierGetter = 'get' . ucfirst($this->transformers[$type]->getIdentifierField());

            foreach($transformedObjects as $object) {
                $transformed[$type][$object->$identifierGetter()] = $object;    
            }
        }

        $result = array();
        foreach ($elasticaObjects as $object) {
            if (array_key_exists($object->getId(), $transformed[$object->getType()])) {
                $result[] = $transformed[$object->getType()][$object->getId()];
            }
        }

        return $result;
    }

    public function hybridTransform(array $elasticaObjects)
    {
        $objects = $this->transform($elasticaObjects);

        $result = array();
        for ($i = 0; $i < count($elasticaObjects); $i++) {
            $result[] = new HybridResult($elasticaObjects[$i], $objects[$i]);
        }

        return $result;
    }
}
