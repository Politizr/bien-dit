<?php
namespace Politizr\AdminBundle\Listener;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Model\POrder;
use Politizr\Model\POPDF;

use Politizr\Model\POPaymentType;
use Politizr\Model\POPaymentState;
use Politizr\Model\POOrderState;
use Politizr\Model\POSubscription;

use Politizr\Exception\InconsistentDataException;

/**
 *  Génération PDF
 *
 *  @author Lionel Bouzonville
 */
class PDFListener
{

    protected $kernel;
    protected $mailer;
    protected $templating;
    protected $html2pdf;
    protected $logger;


    /**
     *
     */
    public function __construct($kernel, $mailer, $templating, $html2pdf, $logger)
    {
        $this->kernel = $kernel;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->html2pdf = $html2pdf;
        $this->logger = $logger;
    }

    /**
     *
     * @param GenericEvent
     */
    public function onOrderPdf(GenericEvent $event)
    {
        $this->logger->info('*** onOrderPdf');

        $order = $event->getSubject();
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
        $invoiceDir = $this->kernel->getRootDir() . '/../web/uploads/invoices/';
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
