<?php

namespace Politizr\Constant;

/**
 * Classe stockant les constantes associés à la gestion de la réputation
 * cf https://docs.google.com/document/d/1TJtr-a17c4e2mlHyCizBCC-UKGPsFYrsD59PeN2Uiks/edit#heading=h.lgtqz6a76v9q
 *
 *
 * @author Lionel Bouzonville
 */
class ReputationConstants {
    /* ######################################################################################################## */
    /*                    NOTES MINIMALES A POSSÉDER POUR LES ACTIONS CITOYENS                                  */
    /* ######################################################################################################## */

	const ACTION_COMMENT_NOTE_POS = 5;
	const ACTION_REACTION_NOTE_POS = 10;
	const ACTION_DEBATE_NOTE_POS = 10;

	const ACTION_COMMENT_NOTE_NEG = 50;
	const ACTION_REACTION_NOTE_NEG = 100;
	const ACTION_DEBATE_NOTE_NEG = 100;
	
	const ACTION_COMMENT_WRITE = 5;
	const ACTION_DEBATE_write = 100;
	
}