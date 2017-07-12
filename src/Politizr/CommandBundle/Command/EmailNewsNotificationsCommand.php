<?php
namespace Politizr\CommandBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

// use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PUserQuery;

use Politizr\Model\PUser;
use Politizr\Model\PMEmailing;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\EmailConstants;
use Politizr\Constant\NotificationConstants;

use Politizr\Exception\PolitizrException;

/**
 * Politizr news notifications email sending
 *
 * https://github.com/Politizr/Politizr/wiki/Liste-des-notifications-mail
 * 
 * @author Lionel Bouzonville
 */
class EmailNewsNotificationsCommand extends ContainerAwareCommand
{
    private $logger;
    private $monitoringManager;
    private $notificationService;
    private $documentService;
    private $mailer;
    private $transport;
    private $templating;

    protected function configure()
    {
        $this
            ->setName('politizr:email:news')
            ->setDescription('Send news notifications')
            ->addArgument(
                'beginAt',
                InputArgument::OPTIONAL,
                'BeginAt (DateTime format)'
            )
            ->addArgument(
                'endAt',
                InputArgument::OPTIONAL,
                'BeginAt (DateTime format)'
            )
            ->addOption(
                'userId',
                null,
                InputOption::VALUE_OPTIONAL,
                'If set, the task will send notification only for this user id'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isVerbose = (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity());

        // services
        $this->logger = $this->getContainer()->get('logger');
        $this->monitoringManager = $this->getContainer()->get('politizr.manager.monitoring');
        $this->notificationService = $this->getContainer()->get('politizr.functional.notification');
        $this->documentService = $this->getContainer()->get('politizr.functional.document');
        $this->mailer = $this->getContainer()->get('mailer');
        $this->transport = $this->getContainer()->get('swiftmailer.transport.real');
        $this->templating = $this->getContainer()->get('templating');

        // initialize begin & end dates
        $beginAt = $input->getArgument('beginAt');
        if ($beginAt) {
            $beginAt = new \DateTime($beginAt);
        } else {
            $beginAt = new \DateTime('yesterday');
            $beginAt->setTime(17, 0, 1);
        }
        $endAt = $input->getArgument('endAt');
        if ($endAt) {
            $endAt = new \DateTime($endAt);
        } else {
            $endAt = new \DateTime('now');
            $endAt->setTime(17, 0, 0);
        }

        // get option
        $userId = $input->getOption('userId');

        // get list of active & daily subscribed users
        $users = PUserQuery::create()
            ->_if($userId)
                ->filterById($userId)
            ->_endif()
            ->distinct()
            ->online()
            ->joinPUSubscribePNE()
            ->find();

        foreach ($users as $user) {
            $output->write('.');

            $puNotifications = $this->notificationService->getUserNotifications($user, $beginAt, $endAt);

            // New elected users
            $followedDebatesPublications = $this->notificationService->getNearestQualifiedUsers($user, $beginAt, $endAt, EmailConstants::NB_MAX_NEW_ELECTED_USERS);

        }

        $spool = $this->mailer->getTransport()->getSpool();
        $nbSent = $spool->flushQueue($this->transport);

        $output->writeln('');
        $output->writeln(sprintf('<info>Send news notifications completed. %s mails have been sent!</info>', $nbSent));
    }
 }
