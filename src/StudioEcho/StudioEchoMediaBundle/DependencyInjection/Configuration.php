<?php

namespace StudioEcho\StudioEchoMediaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('studio_echo_media');

        $rootNode
            ->children()
                ->arrayNode('medias')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('type')
                                ->isRequired()
                                ->validate()
                                    ->ifNotInArray(array('image', 'document'))
                                    ->thenInvalid('Invalid type %s')
                                ->end()
                                ->defaultValue('image')
                                ->info('Value has to be \'image\' or \'document\'.')
                            ->end()
                            ->arrayNode('extension_list')
                                ->treatNullLike(array())
                                ->prototype('scalar')->end()
                                ->defaultValue(array())
                                ->info('List of authorized upload\'s files extensions. If not set, all files extensions are authorized.')
                                ->example('[ jpg, jpeg, png ]')
                            ->end()
                            ->scalarNode('max_files')
                                ->defaultValue('10')
                                ->info('Max number of authorized upload\'s files.')
                            ->end()
                            ->scalarNode('max_size')
                                ->defaultValue('5')
                                ->info('Max size in Mo of authorized upload\'s files.')
                            ->end()
                            ->scalarNode('culture')
                                ->defaultValue('fr')
                                ->info('Default culture of medias gallery.')
                            ->end()
                            ->scalarNode('category_id')
                                ->isRequired()
                                ->info('Category id if you manage several gallery for one object type: each gallery has to have a different category id.')
                            ->end()
                            ->booleanNode('keep_file_name')
                                ->defaultValue(false)
                                ->info('True if you want to keep the original file name when uploading.')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
