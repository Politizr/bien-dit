<?php
namespace Politizr\AdminBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Politizr\AdminBundle\PolitizrAdminEvents;
use Politizr\AdminBundle\Event\PDFEvent;

use Politizr\Model\POrder;
use Politizr\Model\POPDF;

use Politizr\Model\POPaymentType;
use Politizr\Model\POPaymentState;
use Politizr\Model\POOrderState;
use Politizr\Model\POSubscription;

use Politizr\Exception\InconsistentDataException;

/**
 *
 */
class PDFSubscriber implements EventSubscriberInterface
{
    // Services SF2
    private $logger;
    private $mailer;
    private $templating;

    // Services tiers
    private $html2pdf;

    public function __construct($logger, $mailer, $templating, $html2pdf)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->templating = $templating;

        $this->html2pdf = $html2pdf;
    }

    public static function getSubscribedEvents()
    {
        // Liste des évènements écoutés et méthodes à appeler
        return array(
            PolitizrAdminEvents::PDF_ORDER_CUSTOMER => 'pdfOrder'
        );
    }

    public function pdfOrder(PDFEvent $event)
    {
        $this->logger->info('*** pdfOrder');

        $order = $event->getPOrder();
        if (!$order) {
            throw new InconsistentDataException('POrder not found');
        }

        // get current date
        $nowAt = new \DateTime();

        // construct the html rendering
        $htmlInvoice = $this->templating->render(
                                    'PolitizrAdminBundle:POrderEdit:invoice.html.twig', 
                                    array(
                                        'order' => $order, 
                                        'nowAt' => $nowAt
                                        )
                            );

        // $this->logger->info('$htmlInvoice = '.print_r($htmlInvoice, true));
        $html2pdf = $this->html2pdf->get();
        $html2pdf->writeHTML($htmlInvoice);

        // Close and output PDF document
        $invoiceDir = $event->getInvoiceDir();
        $invoiceFilename = 'facture-'.uniqid().'.pdf';

        $content = $html2pdf->Output($invoiceDir . $invoiceFilename, 'F');

        $this->logger->info('$dir = '.print_r($invoiceDir, true));
        $this->logger->info('$invoiceFilename = '.print_r($invoiceFilename, true));

        // Remove previous invoice
        $oldInvoice = $order->getInvoiceFilename();
        if ($oldInvoice && file_exists($invoiceDir . $oldInvoice)) {
            unlink($invoiceDir . $oldInvoice);
        }

        // MAJ de l'objet commande
        $order->setInvoiceAt($nowAt);
        $order->setInvoiceFilename($invoiceFilename);
        $order->save();   
    }
}