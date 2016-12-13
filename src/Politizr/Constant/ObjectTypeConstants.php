<?php

namespace Politizr\Constant;

/**
 * Object type constants
 *
 * @author Lionel Bouzonville
 */
class ObjectTypeConstants
{
    const TYPE_DEBATE = 'Politizr\Model\PDDebate';
    const TYPE_REACTION = 'Politizr\Model\PDReaction';
    const TYPE_DEBATE_COMMENT = 'Politizr\Model\PDDComment';
    const TYPE_REACTION_COMMENT = 'Politizr\Model\PDRComment';
    const TYPE_USER = 'Politizr\Model\PUser';
    const TYPE_TAG = 'Politizr\Model\PTag';
    const TYPE_LOCALIZATION = 'Politizr\FrontBundle\Lib\PLocalization';
    const TYPE_LOCALIZATION_COUNTRY = 'Politizr\Model\PLCountry';
    const TYPE_LOCALIZATION_REGION = 'Politizr\Model\PLRegion';
    const TYPE_LOCALIZATION_DEPARTMENT = 'Politizr\Model\PLDepartment';
    const TYPE_LOCALIZATION_CITY = 'Politizr\Model\PLCity';
    const TYPE_BADGE = 'Politizr\Model\PRBadge';
    const TYPE_ABUSE = 'Politizr\Model\PMAbuseReporting';
    const TYPE_ASK_FOR_UPDATE = 'Politizr\Model\PMAskForUpdate';
    const TYPE_ACTION = 'Politizr\Model\PRAction';

    const CONTEXT_DEBATE = 'debate';
    const CONTEXT_REACTION = 'reaction';
    const CONTEXT_COMMENT = 'comment';
    const CONTEXT_USER = 'user';
    const CONTEXT_PUBLICATION = 'publication';
}
