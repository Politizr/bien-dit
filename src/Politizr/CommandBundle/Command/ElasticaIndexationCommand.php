<?php
namespace Politizr\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Politizr\CommandBundle\Exception\CommandException;

/**
 * Index Politizr documents in Elastica
 * @todo can we use directly "app/console fos:elastica:populate" instead? both?
 * cf. http://www.craftitonline.com/2011/06/calling-commands-within-commands-in-symfony2/
 *
 * @author Studio Echo
 */
class ElasticaIndexationCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('politizr:elastica:indexation')
            ->setDescription('Index Politizr documents in Elastica')
            // @todo add "from" (date) param
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isVerbose = (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity());

        $output->writeln('<info>Politizr Elastica indexation successfully completed.</info>');
    }
}
