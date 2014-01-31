<?php

namespace StudioEcho\StudioEchoGmapsBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('studio_echo_gmaps');

        $rootNode
            ->children()
                ->arrayNode('api_init')
                    ->isRequired()
                    ->children()
                        ->scalarNode('api_key')
                            ->isRequired()
                            ->info('Google API Key')
                        ->end()
                        ->booleanNode('sensor')
                            ->defaultFalse()
                            ->info('Add current position to the map initialization.')
                        ->end()
                        ->integerNode('version')
                            ->defaultValue(3)
                            ->info('API version')
                        ->end()
                        ->scalarNode('region')
                            ->info('Add API region to the map initialization.')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('options')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->integerNode('zoom_level')
                                ->isRequired()
                                ->defaultValue(3)
                                ->info('Gmaps zoom init level.')
                            ->end()
                            ->scalarNode('center')
                                ->defaultValue('46, 2')
                                ->info('Gmaps latitude / longitude marker initial position.')
                            ->end()
                            ->scalarNode('scrool_wheel')
                                ->defaultFalse()
                                ->info('Scrool wheel.')
                            ->end()
                            ->scalarNode('map_type_id')
                                ->defaultValue('ROADMAP')
                                ->validate()
                                    ->ifNotInArray(array('HYBRID', 'ROADMAP', 'SATELLITE', 'TERRAIN'))
                                    ->thenInvalid('Invalid type %s')
                                ->end()                                
                                ->info('Google maps type: HYBRID, ROADMAP, SATELLITE, TERRAIN.')
                            ->end()
                        ->end()
                    ->end()
                ->end()                
            ->end()
        ;

        return $treeBuilder;
    }
}
