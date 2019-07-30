<?php

namespace Nines\EditorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nines_editor');

        $rootNode->children()
            ->arrayNode('plugins')
                ->treatNullLike(array())
                ->prototype('scalar')->end()
                ->defaultValue(array('image', 'imagetools', 'link', 'lists', 'paste', 'wordcount'))
                ->end()
            ->arrayNode('menubar')
                ->treatNullLike(array())
                ->prototype('scalar')->end()
                ->defaultValue(array('edit', 'insert', 'view', 'format', 'tools'))
                ->end()
            ->arrayNode('toolbar')
                ->arrayPrototype()
                    ->treatNullLike(array())
                    ->prototype('scalar')->end()
                    ->defaultValue(array(
                        ['undo', 'redo'],
                        ['selectstyle'],
                        ['paste'],
                        ['bold', 'italic'],
                        ['alignleft', 'aligncenter', 'alignright', 'alignjustify'],
                        ['bulllist', 'numlist', 'outdent', 'indent'],
                        ['link', 'image'],
                    ))
                    ->end()
                ->end()
            ->arrayNode('images')
                ->children()
                    ->booleanNode('caption')->defaultValue(true)->end()
                    ->booleanNode('credentials')->defaultValue(true)->end()
                    ->booleanNode('advanced_tab')->defaultValue(true)->end()
                    ->booleanNode('title')->defaultValue(true)->end()
                    ->end()
                ->end()
        ;

        return $treeBuilder;
    }
}
