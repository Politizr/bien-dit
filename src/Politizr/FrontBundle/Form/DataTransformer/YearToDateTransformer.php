<?php

namespace Politizr\FrontBundle\Form\DataTransformer;

// use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;

/**
 *
 */
class YearToDateTransformer implements DataTransformerInterface
{
    /**
     * DateTime to year string
     *
     * @param \DateTime $dateTime
     * @return string
     */
    public function transform($dateTime)
    {
        if (null === $dateTime) {
            return null;
        }

        $year = $dateTime->format('Y');
        return $year;
    }

    /**
     * Year string to DateTime
     *
     * @param string $year
     * @return \DateTime
     */
    public function reverseTransform($year)
    {
        if (empty($year)) {
            return null;
        }

        if (!is_numeric($year) || strlen($year) != 4) {
            return null;
        }

        $dateTime = new \DateTime();
        $dateTime->setDate($year, 1, 1);

        return $dateTime;
    }
}
