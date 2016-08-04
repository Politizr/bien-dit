<?php
namespace Politizr\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Politizr\Model\PUserQuery;
use Politizr\Model\PNotificationQuery;
use Politizr\Model\PUSubscribeEmailQuery;

use Politizr\Model\PUSubscribeEmail;

/**
 * Politizr force email subscription
 * command to update db cf #523
 *
 * @author Lionel Bouzonville
 */
class NotificationEmailSubscribeCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('politizr:notif:subscribe')
            ->setDescription('Subscribe users to notification email')
            ->addArgument(
                'notifId',
                InputArgument::REQUIRED,
                'specific notif id'
            )
            ->addArgument(
                'from',
                InputArgument::OPTIONAL,
                'start from specified user id'
            )
            ->addArgument(
                'to',
                InputArgument::OPTIONAL,
                'end to specified user id'
            )
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isVerbose = (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity());

        $notifId = $input->getArgument('notifId');

        $fromUserId = $input->getArgument('from');

        $toUserId = $input->getArgument('to');

        $users = PUserQuery::create()
            ->_if($fromUserId)
                ->filterById($fromUserId, \Criteria::GREATER_EQUAL)
            ->_endIf()
            ->_if($toUserId)
                ->filterById($toUserId, \Criteria::LESS_EQUAL)
            ->_endIf()
            ->find();

        $con = \Propel::getConnection('default');
        $con->beginTransaction();
        try {
            $notif = PNotificationQuery::create()->findPk($notifId);
            $counter = 0;
            if ($notif) {
                foreach ($users as $user) {
                    $puSubscribeEmail = PUSubscribeEmailQuery::create()
                        ->filterByPUserId($user->getId())
                        ->filterByPNotificationId($notif->getId())
                        ->findOne();

                    if (!$puSubscribeEmail) {
                        $puSubscribeEmail = new PUSubscribeEmail();

                        $puSubscribeEmail->setPUserId($user->getId());
                        $puSubscribeEmail->setPNotificationId($notif->getId());

                        $puSubscribeEmail->save();

                        $counter++;
                    }
                }
            }
            $con->commit();
        } catch (\Exception $e) {
            $con->rollback();
            throw new \Exception('Rollback - msg = '.print_r($e->getMessage(), true));
        }

        $output->writeln(sprintf('<info>%s user\'s email\'s notifications subscriptions have been successfully updated.</info>', $counter));
    }
}
