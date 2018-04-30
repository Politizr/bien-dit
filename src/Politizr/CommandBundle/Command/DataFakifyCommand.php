<?php
namespace Politizr\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Faker\Factory as FakerFactory;

use Politizr\Model\PUserQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;

/**
 * Politizr fakify command
 *
 * @author Lionel Bouzonville
 */
class DataFakifyCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('politizr:data:fakify')
            ->setDescription('Update Politizr data with fake data')
            ->addArgument(
                'model',
                InputArgument::OPTIONAL,
                'Model object name to fake'
            )
            ->addOption(
                'with-image',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will generate new images'
            )
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isVerbose = (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity());

        $faker = FakerFactory::create('fr_FR');

        $users = PUserQuery::create()->find();

        $con = \Propel::getConnection();
        $con->beginTransaction();
        try {
            foreach ($users as $user) {
                $user->setName($faker->lastName());
                $user->setFirstname($faker->firstName());
                $user->setEmail($faker->email());
                $user->setWebsite($faker->url());
                $user->setBiography($faker->realText(150));

                $user->setUsername($user->getEmail());
                $user->setUsernameCanonical($user->getEmail());
                $user->setEmailCanonical($user->getEmail());

                $user->setFileName(null);
                if ($input->getOption('with-image')) {
                    $user->setFileName($faker->image('/home/lionel/www/politizr.demo/web/uploads/users', 150, 150, 'people', false));
                }

                $user->save();
            }
            $output->writeln(sprintf('%s users updated', count($users)));

            $debates = PDDebateQuery::create()->find();

            foreach ($debates as $debate) {
                $debate->setTitle($faker->realText(50));
                $debate->setDescription(
                    '<h1>'.$faker->realText(50).'</h1>'.
                    '<p>'.$faker->realText(300).'</p>'.
                    '<h2>'.$faker->realText(50).'</h2>'.
                    '<p>'.$faker->realText(300).'</p>'.
                    '<h1>'.$faker->realText(50).'</h1>'.
                    '<blockquote>'.$faker->realText(50).'</blockquote>'.
                    '<p>'.$faker->realText(300).'</p>'
                );

                $debate->setFileName(null);
                if ($input->getOption('with-image')) {
                    $debate->setFileName($faker->image('/home/lionel/www/politizr.demo/web/uploads/documents', 640, 480, 'city', false));
                }

                $debate->save();
            }
            $output->writeln(sprintf('%s debates updated', count($debates)));

            $reactions = PDReactionQuery::create()->find();

            foreach ($reactions as $reaction) {
                $reaction->setTitle($faker->realText(50));
                $reaction->setDescription(
                    '<h1>'.$faker->realText(50).'</h1>'.
                    '<p>'.$faker->realText(300).'</p>'.
                    '<h2>'.$faker->realText(50).'</h2>'.
                    '<p>'.$faker->realText(300).'</p>'.
                    '<h1>'.$faker->realText(50).'</h1>'.
                    '<blockquote>'.$faker->realText(50).'</blockquote>'.
                    '<p>'.$faker->realText(300).'</p>'
                );

                $reaction->setFileName(null);
                if ($input->getOption('with-image')) {
                    $reaction->setFileName($faker->image('/home/lionel/www/politizr.demo/web/uploads/documents', 640, 480, 'city', false));
                }

                $reaction->save();
            }
            $output->writeln(sprintf('%s reactions updated', count($reactions)));

            $comments = PDDCommentQuery::create()->find();

            foreach ($comments as $comment) {
                $comment->setDescription($faker->realText(250));

                $comment->save();
            }
            $output->writeln(sprintf('%s subject comments updated', count($comments)));

            $comments = PDRCommentQuery::create()->find();

            foreach ($comments as $comment) {
                $comment->setDescription($faker->realText(250));

                $comment->save();
            }
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
        $con->commit();
        $output->writeln(sprintf('%s response comments updated', count($comments)));

        $output->writeln('<info>Populate Politizr objects with fake data successfully completed.</info>');
    }
}
