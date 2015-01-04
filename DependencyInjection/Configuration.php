<?php
namespace Landmarx\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('landmarx_core');

        $rootNode->
            children()
                ->scalarNode("breadcrumbs_separator")
                    ->defaultValue("/")
                    ->end()
                ->scalarNode("breadcrumbs_separatorClass")
                    ->defaultValue("separator")
                    ->end()
                ->scalarNode("breadcrumbs_listId")
                    ->defaultValue("landmarx-breadcrumbs")
                    ->end()
                ->scalarNode("breadcrumbs_listClass")
                    ->defaultValue("breadcrumb")
                    ->end()
                ->scalarNode("breadcrumbs_itemClass")
                    ->defaultValue("")
                    ->end()
                ->scalarNode("breadcrumbs_linkRel")
                    ->defaultValue("")
                    ->end()
                ->scalarNode("breadcrumbs_locale")
                    ->defaultNull()
                    ->end()
                ->scalarNode("breadcrumbs_translation_domain")
                    ->defaultNull()
                    ->end()
                ->scalarNode("breadcrumbs_viewTemplate")
                    ->defaultValue("LandmarxResourceBundle:Base:breadcrumbs.html.twig")
                    ->end()
                ->end();

        return $treeBuilder;
    }
}
