<?php

declare(strict_types=1);

namespace App\vBridgeCloud\CallLoginBundle\DependencyInjection;

use App\vBridgeCloud\CallLoginBundle\Auth\AuthEntryPoint;
use App\vBridgeCloud\CallLoginBundle\Auth\JwtAuthenticator;
use App\vBridgeCloud\CallLoginBundle\Auth\UserProvider;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class CallLoginExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('call_login.entrypoint');
        $definition->setArgument('$publicLoginUrl', $config['public_url']);
        $definition->setArgument('$clientId', $config['client_id']);
        $definition->setArgument('$oauthRedirectPath', $config['oauth_redirect_path']);

        $definition = $container->getDefinition('call_login.authenticator');
        $definition->setArgument('$publicLoginUrl', $config['public_url']);
        $definition->setArgument('$internalLoginUrl', $config['internal_url']);
        $definition->setArgument('$clientId', $config['client_id']);
        $definition->setArgument('$clientSecret', $config['client_secret']);
        $definition->setArgument('$oauthRedirectPath', $config['oauth_redirect_path']);
        $definition->setArgument('$loginRedirectPath', $config['login_redirect_path']);

        $definition = $container->getDefinition('call_login.user_provider');
        $definition->setArgument('$internalLoginUrl', $config['internal_url']);
    }
}
