<?php
namespace Politizr\AdminBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Politizr\AdminBundle\PolitizrAdminEvents;
use Politizr\AdminBundle\Event\EmailEvent;

use Politizr\Model\POrder;
use Politizr\Model\POEmail;

use Politizr\Model\POPaymentType;
use Politizr\Model\POPaymentState;
use Politizr\Model\POOrderState;
use Politizr\Model\POSubscription;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\SendEmailException;


/**
 *
 */
class EmailSubscriber implements EventSubscriberInterface
{
    // Services SF2
    private $logger;
    private $mailer;
    private $templating;

    public function __construct($logger, $mailer, $templating)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public static function getSubscribedEvents()
    {
        // Liste des évènements écoutés et méthodes à appeler
        return array(
            PolitizrAdminEvents::EMAIL_ORDER_CUSTOMER => 'emailOrder'
        );
    }

    public function emailOrder(EmailEvent $event)
    {
        $this->logger->info('*** emailOrder');

        $order = $event->getPOrder();
        if (!$order) {
            throw new InconsistentDataException('POrder not found');
        }

        $user = $order->getPUser();
        if (!$user) {
            throw new InconsistentDataException('PUser from POrder pk-' . $order->getId() . ' not found');
        }

        $toEmail = $user->getEmail();
        if (!$toEmail) {
            throw new InconsistentDataException('PUser pk-' . $user->getId() . ' has no email.');
        }


        $template = '';
        $subject = '';

        switch($status = $order->getPOOrderStateId()) {
            case POOrderState::STATE_CREATE:
                $subject = 'Commande créée';
                break;
            case POOrderState::STATE_WAITING:
                $subject = 'Commande en attente';
                break;
            case POOrderState::STATE_OPEN:
                $subject = 'Commande ouverte';
                break;
            case POOrderState::STATE_HANDLED:
                $subject = 'Commande en cours de traitement';
                break;
            case POOrderState::STATE_CANCEL:
                $subject = 'Commande annulée';
                break;
            default: break;
        }

        $htmlBody = $this->templating->render(
                            'PolitizrAdminBundle:Email:orderStateGeneric.html.twig', array('order' => $order, 'user' => $user, 'status' => $order->getPOOrderStateId())
                    );
        $txtBody = $this->templating->render(
                            'PolitizrAdminBundle:Email:orderStateGeneric.txt.twig', array('order' => $order, 'user' => $user, 'status' => $order->getPOOrderStateId())
                    );

        // TODO: gestion propre des envoyeurs / receveur check normes email "prénom nom" <email>
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom(array('contact@politizr.com' => 'Politizr'))
                ->setTo($toEmail)
                // ->setBcc(array('lionel.bouzonville@gmail.com'))
                ->setBody($htmlBody, 'text/html', 'utf-8')
                ->addPart($txtBody, 'text/plain', 'utf-8')
        ;

        // PJ facture PDF
        $invoiceFilename = $order->getInvoiceFilename();
        if ($invoiceFilename && $invoiceDir = $event->getInvoiceDir()) {
            $message->attach(\Swift_Attachment::fromPath($invoiceDir . $invoiceFilename));
        }

        // Envoi email
        $failedRecipients = array();
        $send = $this->mailer->send($message, $failedRecipients);
        $this->logger->debug('$send = '.print_r($send, true));
        if (!$send) {
            // TODO: traitement en cas d'échec de l'envoi de l'email
            throw new SendEmailException($send);
        }

        // Inscription en BDD
        $poEmail = new POEmail();
        $poEmail->setPOrderId($order->getId());

        $poEmail->setPOPaymentStateId($order->getPOPaymentStateId());
        $poEmail->setPOPaymentTypeId($order->getPOPaymentTypeId());
        $poEmail->setPOOrderStateId($order->getPOOrderStateId());
        $poEmail->setPOSubscriptionId($order->getPOSubscriptionId());

        $poEmail->setSubject($subject);
        $poEmail->setHtmlBody($htmlBody);
        $poEmail->setTxtBody($txtBody);
        $poEmail->save();
    }
}