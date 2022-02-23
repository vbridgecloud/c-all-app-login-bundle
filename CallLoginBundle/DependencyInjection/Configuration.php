<?php

declare(strict_types=1);

namespace App\vBridgeCloud\CallLoginBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('vbridgecloud_calllogin');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('public_url')->isRequired()->end()
                ->scalarNode('internal_url')->isRequired()->end()
                ->scalarNode('client_id')->isRequired()->end()
                ->scalarNode('client_secret')->isRequired()->end()
                ->scalarNode('login_redirect_path')->defaultValue('home')->end()
                ->scalarNode('oauth_redirect_path')->defaultValue('call_login_authorize')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
