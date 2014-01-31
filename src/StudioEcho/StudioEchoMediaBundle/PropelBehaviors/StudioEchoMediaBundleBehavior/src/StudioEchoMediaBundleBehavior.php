<?php

/**
 * Create / Delete SeMediaObject associated with current object
 *
 * @author Studio Echo / Lionel Bouzonville
 */
class StudioEchoMediaBundleBehavior extends Behavior {
    /**
     * Create a new SeMediaObject instance
     *
     * @return string The code to put at the hook
     */
    public function postInsert()
    {
      return "
        \$seMediaObject = new \StudioEcho\StudioEchoMediaBundle\Model\SeMediaObject();
        \$seMediaObject->setObjectId(\$this->getId());
        \$seMediaObject->setObjectClassname(get_class(\$this));
        \$seMediaObject->save(\$con);
        ";
    }

    /**
     * Delete the associated SeMediaObject instance
     *
     * @return string The code to put at the hook
     */
    public function postDelete()
    {
      return "
        \$seMediaObject = \StudioEcho\StudioEchoMediaBundle\Model\SeMediaObjectQuery::create()->filterByObjectClassname(get_class(\$this))->filterByObjectId(\$this->getId())->findOne();
        \$seMediaObject->delete(\$con);
        ";
    }

}

