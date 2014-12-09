<?php

namespace Politizr\AdminBundle\Listener;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Model\POOrderState;
use Politizr\Model\POEmail;

/**
 * 	Envoi des emails
 *
 *  @author Lionel Bouzonville
 */
class EmailListener {

    protected $mailer;
    protected $templating;
    protected $logger;

    protected $contactEmail;


    /**
     *
     */
    public function __construct($mailer, $templating, $logger, $contactEmail) {
    	$this->mailer = $mailer;
    	$this->templating = $templating;
    	$this->logger = $logger;

    	$this->contactEmail = $contactEmail;
    }

    /**
     * 
     * @param GenericEvent
     */
    public function onOrderEmail(GenericEvent $event) {
    	$this->logger->info('*** onOrderEmail');

    	$order = $event->getSubject();
        $subject = 'Politizr - ';
        switch ($order->getPOOrderStateId()) {
            case POOrderState::CREATED:
                $template = 'order';
                $subject .= 'Commande créée';
                $pj = null;
                break;
            case POOrderState::WAITING:
                $template = 'order';
                $subject .= 'Commande en attente';
                $pj = null;
                break;
            case POOrderState::OPEN:
                $template = 'order';
                $subject .= 'Commande ouverte';
                $pj = null;
                break;
            case POOrderState::HANDLED:
                $template = 'order';
                $subject .= 'Commande traitée';
                $pj = null;
                $pj = $order->getInvoiceFilename();
                break;
            case POOrderState::CANCELED:
                $template = 'order';
                $subject .= 'Commande annulée';
                $pj = null;
                $pj = $order->getInvoiceFilename();
                break;
        }

        try {
            $user = $order->getPUser();

            $htmlBody = $this->templating->render(
                                'PolitizrAdminBundle:Email:'.$template.'.html.twig', array('order' => $order, 'user' => $user)
                        );
            $txtBody = $this->templating->render(
                                'PolitizrAdminBundle:Email:'.$template.'.txt.twig', array('order' => $order, 'user' => $user)
                        );

            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom(array($this->contactEmail => 'Politizr'))
                    ->setTo($order->getEmail())
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;

            // Facture en PJ
            if ($pj) {
            	$invoiceDir = '/uploads/invoices/';
                $message->attach(\Swift_Attachment::fromPath($invoiceDir . $pj));
            }

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            // Sauvergarde envoi en BDD
            $orderEmail = new POEmail();
            $orderEmail->setSend($send);
            $orderEmail->setPOrderId($order->getId());
            $orderEmail->setPOOrderStateId($order->getPOOrderStateId());
            $orderEmail->setPOPaymentStateId($order->getPOPaymentStateId());
            $orderEmail->setPOPaymentTypeId($order->getPOPaymentTypeId());
            $orderEmail->setSubject($subject);
            $orderEmail->setHtmlBody($htmlBody);
            $orderEmail->setTxtBody($txtBody);
            $orderEmail->save();

            $this->logger->info('send = '.print_r($send, true));
            if (!$send) {
                throw new \Exception('email non envoyé - code retour = '.$send.' - adresse(s) en échec = '.print_r($failedRecipients, true));
            }
        } catch (\Exception $e) {
            if (null !== $this->logger) {
                $this->logger->err('Exception - message = '.$e->getMessage());
            }
            
            throw $e;
        }
    }

}
