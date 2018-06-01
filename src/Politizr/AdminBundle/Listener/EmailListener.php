<?php

namespace Politizr\AdminBundle\Listener;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\SendEmailException;

use Politizr\Constant\OrderConstants;

use Politizr\Model\PUserQuery;

use Politizr\Model\POEmail;

/**
 * Emails
 *
 * @author Lionel Bouzonville
 */
class EmailListener
{

    protected $mailer;
    protected $templating;
    protected $logger;

    protected $contactEmail;
    protected $supportEmail;


    /**
     *
     * @param @mailer
     * @param @templating
     * @param @logger
     * @param "%contact_email%"
     * @param "%support_email%"
     */
    public function __construct(
        $mailer,
        $templating,
        $logger,
        $contactEmail,
        $supportEmail
    ) {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->logger = $logger;

        $this->contactEmail = $contactEmail;
        $this->supportEmail = $supportEmail;
    }

    /**
     * Order email
     *
     * @param GenericEvent
     */
    public function onOrderEmail(GenericEvent $event)
    {
        $this->logger->info('*** onOrderEmail');

        $order = $event->getSubject();
        $subject = '[ Politizr ] ';
        switch ($order->getPOOrderStateId()) {
            case OrderConstants::ORDER_CREATED:
                $template = 'order';
                $subject .= 'Commande créée';
                $pj = null;
                break;
            case OrderConstants::ORDER_WAITING:
                $template = 'order';
                $subject .= 'Commande en attente';
                $pj = null;
                break;
            case OrderConstants::ORDER_OPEN:
                $template = 'order';
                $subject .= 'Commande ouverte';
                $pj = null;
                break;
            case OrderConstants::ORDER_HANDLED:
                $template = 'order';
                $subject .= 'Commande traitée';
                $pj = null;
                $pj = $order->getInvoiceFilename();
                break;
            case OrderConstants::ORDER_CANCELED:
                $template = 'order';
                $subject .= 'Commande annulée';
                $pj = null;
                $pj = $order->getInvoiceFilename();
                break;
            default:
                throw InconsistentDataException(sprintf('Order type %s is not defined.', $order->getPOOrderStateId()));
        }

        try {
            $user = $order->getPUser();

            $htmlBody = $this->templating->render(
                'PolitizrAdminBundle:Email:'.$template.'.html.twig',
                array('order' => $order, 'user' => $user)
            );
            $txtBody = $this->templating->render(
                'PolitizrAdminBundle:Email:'.$template.'.txt.twig',
                array('order' => $order, 'user' => $user)
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom(array($this->contactEmail => 'Support@Politizr'))
                    ->setTo($order->getEmail())
                    // ->setBcc(array('lionel@politizr.com'))
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'Order billing');

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
            
            throw new SendEmailException($e->getMessage(), $e);
        }
    }

    /**
     * Moderation's notification email
     *
     * @param GenericEvent
     */
    public function onModerationNotification(GenericEvent $event)
    {
        $this->logger->info('*** onModerationNotification');

        $userModerated = $event->getSubject();

        try {
            $htmlBody = $this->templating->render(
                'PolitizrAdminBundle:Email:moderationNotification.html.twig',
                array(
                    'userModerated' => $userModerated,
                )
            );
            $txtBody = $this->templating->render(
                'PolitizrAdminBundle:Email:moderationNotification.txt.twig',
                array(
                    'userModerated' => $userModerated,
                )
            );

            $user = PUserQuery::create()->findPk($userModerated->getPUserId());
            if (!$user) {
                throw new InconsistentDataException('User null');
            }

            $message = \Swift_Message::newInstance()
                    ->setSubject('[ Politizr ] Modération')
                    ->setFrom(array($this->contactEmail => 'Support@Politizr'))
                    ->setTo($user->getEmail())
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'Moderation notif');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            $this->logger->info('send = '.print_r($send, true));
            if (!$send) {
                throw new \Exception('email non envoyé - code retour = '.$send.' - adresse(s) en échec = '.print_r($failedRecipients, true));
            }
        } catch (\Exception $e) {
            if (null !== $this->logger) {
                $this->logger->err('Exception - message = '.$e->getMessage());
            }
            
            throw new SendEmailException($e->getMessage(), $e);
        }
    }

    /**
     * Moderation's banned email
     *
     * @param GenericEvent
     */
    public function onModerationBanned(GenericEvent $event)
    {
        $this->logger->info('*** onModerationBanned');

        $user = $event->getSubject();

        try {
            if (!$user) {
                throw new InconsistentDataException('User null');
            }

            $htmlBody = $this->templating->render(
                'PolitizrAdminBundle:Email:moderationBanned.html.twig',
                array(
                    'user' => $user,
                )
            );
            $txtBody = $this->templating->render(
                'PolitizrAdminBundle:Email:moderationBanned.txt.twig',
                array(
                    'user' => $user,
                )
            );

            $message = \Swift_Message::newInstance()
                    ->setSubject('[ Politizr ] Bannissement')
                    ->setFrom(array($this->contactEmail => 'Support@Politizr'))
                    ->setTo($user->getEmail())
                    ->setBody($htmlBody, 'text/html', 'utf-8')
                    ->addPart($txtBody, 'text/plain', 'utf-8')
            ;
            $message->getHeaders()->addTextHeader('X-CMail-GroupName', 'Moderation bannissement');

            // Envoi email
            $failedRecipients = array();
            $send = $this->mailer->send($message, $failedRecipients);

            $this->logger->info('send = '.print_r($send, true));
            if (!$send) {
                throw new \Exception('email non envoyé - code retour = '.$send.' - adresse(s) en échec = '.print_r($failedRecipients, true));
            }
        } catch (\Exception $e) {
            if (null !== $this->logger) {
                $this->logger->err('Exception - message = '.$e->getMessage());
            }
            
            throw new SendEmailException($e->getMessage(), $e);
        }
    }
}
