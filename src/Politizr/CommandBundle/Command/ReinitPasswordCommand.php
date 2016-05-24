<?php
namespace Politizr\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Politizr\Model\PUserQuery;

/**
 * Politizr reinit password for inactive accounts
 *
 * @author Lionel Bouzonville
 */
class ReinitPasswordCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('politizr:password:reinit')
            ->setDescription('Reinit inactive account with beta password')
            ->addArgument(
                'password',
                InputArgument::OPTIONAL,
                'Password to set'
            )
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isVerbose = (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity());

        $password = $input->getArgument('password');
        if (!$password) {
            $password = 'beta';
        }

        $users = PUserQuery::create()
            ->filterByLastActivity(null)
            ->find();


        $con = \Propel::getConnection('default');
        $con->beginTransaction();
        try {
            foreach ($users as $user) {
                $output->writeln(sprintf('Manage %s', $user));
                $user->setPlainPassword('beta');
                $this->getContainer()->get('politizr.manager.user')->updatePassword($user);
                $user->save();
            }
            $con->commit();
        } catch (\Exception $e) {
            $con->rollback();
            throw new \Exception('Rollback - msg = '.print_r($e->getMessage(), true));
        }

        $output->writeln(sprintf('<info>%s user\'s passwords have been successfully updated.</info>', count($users)));
    }
}
