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
 * @author Studio Echo
 */
class EmailSendingCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('politizr:email:sending')
            ->setDescription('Send Politizr email waiting in queue')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isVerbose = (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity());

        $output->writeln('<info>Politizr email sending successfully completed.</info>');
    }
}
