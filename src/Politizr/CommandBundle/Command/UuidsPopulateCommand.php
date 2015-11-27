<?php
namespace Politizr\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Politizr\CommandBundle\Exception\CommandException;

/**
 * Politizr email sending
 *
 * @author Lionel Bouzonville
 */
class UuidsPopulateCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('politizr:uuids:populate')
            ->setDescription('Populate Politizr objects with UUID')
            ->addArgument(
                'model',
                InputArgument::OPTIONAL,
                'Model object name to update'
            )
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will force update of uuid event if not null'
            )
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isVerbose = (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity());

        $model = $input->getArgument('model');
        if ($model) {
            $queryClasses = [ 'Politizr\Model\\' . $model . 'Query' ];
        } else {
            $queryClasses = [
                'Politizr\Model\PTagQuery',
                'Politizr\Model\PRBadgeQuery',
                'Politizr\Model\PRActionQuery',
                'Politizr\Model\POrderQuery',
                'Politizr\Model\POSubscriptionQuery',
                'Politizr\Model\PQualificationQuery',
                'Politizr\Model\PQTypeQuery',
                'Politizr\Model\PQMandateQuery',
                'Politizr\Model\PQOrganizationQuery',
                'Politizr\Model\PUserQuery',
                'Politizr\Model\PDDebateQuery',
                'Politizr\Model\PDReactionQuery',
                'Politizr\Model\PDDCommentQuery',
                'Politizr\Model\PDRCommentQuery',
                'Politizr\Model\PNotificationQuery',
                'Politizr\Model\PUNotificationQuery',
                'Politizr\Model\PUMandateQuery',
                ]
            ;
        }

        foreach ($queryClasses as $queryClass) {
            $output->writeln(sprintf('Manage %s', $queryClass));
            if ($input->getOption('force')) {
                $query = $queryClass::create();
            } else {
                $query = $queryClass::create()->filterByUuid(null);
            }
            $subjects = $query->find();
            foreach ($subjects as $subject) {
                $output->write('.');
                $subject->setUuid(\Ramsey\Uuid\Uuid::uuid4()->__toString());
                $subject->save();
            }
            $output->writeln(sprintf('%s objects updated', count($subjects)));
        }

        $output->writeln('<info>Populate Politizr objects with UUID successfully completed.</info>');
    }
}
