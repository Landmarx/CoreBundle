<?php
namespace Landmarx\Bundle\CoreBundle;

use \Sylius\Bundle\ResourceBundle\SyliusResourceBundle;

class LandmarxCoreBundle extends \Sylius\Bundle\ResourceBundle\AbstractResourceBundle
{
    /**
     * {@inheritdoc}
     */
    public static function getSupportedDrivers()
    {
        return array(
            SyliusResourceBundle::DRIVER_DOCTRINE_MONGODB_ODM
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function build(\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        parent::build($container);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function getBundlePrefix()
    {
        return 'landmarx_core';
    }
}
