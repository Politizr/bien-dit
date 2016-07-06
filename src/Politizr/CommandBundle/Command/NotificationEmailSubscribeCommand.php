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

        $fromUserId = $input->getArgument('from');
        if (!$fromUserId) {
            $fromUserId = 299;
        }

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
            foreach ($users as $user) {
                $notifications = PNotificationQuery::create()->find();
                foreach ($notifications as $notif) {
                    $puSubscribeEmail = PUSubscribeEmailQuery::create()
                        ->filterByPUserId($user->getId())
                        ->filterByPNotificationId($notif->getId())
                        ->findOne();

                    if (!$puSubscribeEmail) {
                        $puSubscribeEmail = new PUSubscribeEmail();

                        $puSubscribeEmail->setPUserId($user->getId());
                        $puSubscribeEmail->setPNotificationId($notif->getId());

                        $puSubscribeEmail->save();
                    }
                }
            }
            $con->commit();
        } catch (\Exception $e) {
            $con->rollback();
            throw new \Exception('Rollback - msg = '.print_r($e->getMessage(), true));
        }

        $output->writeln(sprintf('<info>%s user\'s email\'s notifications subscribtions have been successfully updated.</info>', count($users)));
    }
}
