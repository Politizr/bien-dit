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
            ->setDescription('Populate Politizr objects with UUID if null')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isVerbose = (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity());

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
            ]
        ;

        foreach ($queryClasses as $queryClass) {
            $subjects = $queryClass::create()->filterByUuid(null)->find();
            foreach ($subjects as $subject) {
                $subject->setUuid(\Ramsey\Uuid\Uuid::uuid1()->__toString());
                $subject->save();
            }
        }

        $output->writeln('<info>Populate Politizr objects with UUID if null successfully completed.</info>');
    }
}
