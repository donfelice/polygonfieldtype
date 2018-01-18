<?php

namespace Donfelice\PolygonFieldTypeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class DonfelicePolygonFieldTypeExtension extends Extension implements PrependExtensionInterface
{

    /**
     * {@inheritdoc}
     */
    public function load( array $configs, ContainerBuilder $container )
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration( $configuration, $configs );

        $loader = new Loader\YamlFileLoader( $container, new FileLocator( __DIR__.'/../Resources/config' ) );
        $loader->load('services.yml');
        $loader->load('fieldtypes.yml');
    }

    public function prepend( ContainerBuilder $container )
    {
        $configs = array(
            'ezplatform.yml' => 'ezpublish',
            //'framework/twig.yml' => 'twig',
            'ezadminui/twig.yml' => 'twig',
        );

        foreach ( $configs as $fileName => $extensionName ) {
            $configFile = __DIR__ . '/../Resources/config/' . $fileName;
            $config = Yaml::parse( file_get_contents( $configFile ) );
            $container->prependExtensionConfig( $extensionName, $config );
            $container->addResource( new FileResource( $configFile ) );
        }

    }

}
