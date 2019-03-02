<?php
namespace Politizr\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

// use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use AlgoliaSearch\Client as AlgoliaSearchClient;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PUserQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;

use Politizr\Model\PUserArchiveQuery;
use Politizr\Model\PDDebateArchiveQuery;
use Politizr\Model\PDReactionArchiveQuery;

use Politizr\Constant\ObjectTypeConstants;

use Politizr\Exception\PolitizrException;

/**
 * Politizr db indexing to Algolia
 *
 * @author Lionel Bouzonville
 */
class AlgoliaInitAttributesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('politizr:algolia:init')
            ->setDescription('Algolia index attributes initialization')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isVerbose = (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity());

        $appId = $this->getContainer()->getParameter('algolia_app_id');
        $apiKey = $this->getContainer()->getParameter('algolia_admin_api_key');
        $indexName = $this->getContainer()->getParameter('algolia_index_name');

        // Algolia client init
        $client = new AlgoliaSearchClient($appId, $apiKey);
        $index = $client->initIndex($indexName);

        $index->setSettings(array(
            'searchableAttributes' => array(
                'title',
                'description',
            ),
            'attributesForFaceting' => array(
                'typeLabel',
                'filterOnly(circleUuid)',
            ),
            'attributesToSnippet' => array(
                'description:50',
            ),
            'snippetEllipsisText' => '(...)',
            'customRanking' => array(
                'desc(popularity)'
            )
        ));

        $now = new \DateTime('now');
        $output->writeln('');
        $output->writeln(sprintf('<info>%s - Algolia init attributes completed</info>', $now->format('Y-m-d H:i:s')));
    }
}