<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Comment type for debate & reaction
 * /!\ do not use directly, use PDDCommentType or PDRComment type instead
 *
 * @author Lionel Bouzonville
 */
class PDCommentType extends AbstractType
{
    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('paragraph_no', 'hidden', array(
            'required' => false,
        ));
        
        $builder->add('description', 'textarea', array(
            'required' => false,
            'label' => 'Commentaire',
            'constraints' => array(
                new NotBlank(array('message' => 'Commentaire obligatoire.')),
                new Length(array('min' => 5, 'max' => 500, 'minMessage' => 'Au moins 5 caractères.', 'maxMessage' => 'Maximum de 500 caractères.')),
            ),
            'attr' => array(
                'placeholder' => 'Votre commentaire...',
                )
        ));

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $comment = $event->getForm()->getData();

            $cleanedDescription = $comment->getDescription();

            // strip html tags
            $cleanedDescription = strip_tags($cleanedDescription);
            // transform \n => <br>
            $cleanedDescription = nl2br($cleanedDescription);

            $comment->setDescription($cleanedDescription);
        });
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'comment';
    }
}
