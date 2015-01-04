<?php
namespace Landmarx\Bundle\CoreBundle\Listener;

use \Symfony\Component\HttpKernel\HttpKernel;

class CoreListener
{
    /**
     * container
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    /**
     * __construct
     * @param \Symfony\Component\DependencyInjection\Container $container
     */
    public function __construct(\Symfony\Component\DependencyInjection\Container $container)
    {
        $this->container = $container;
    }

    /**
     * Get api key
     * @return String api key
     */
    public function getApiKey()
    {
        return $this->container->getParameter('api_key');
    }

    /**
     * Get api secret
     * @return String api secret
     */
    public function getApiSecret()
    {
        return $this->container->getParameter('api_secret');
    }

    /**
     * On kernel request
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     * @return mixed
     */
    public function onKernelRequest(\Symfony\Component\HttpKernel\Event\GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }
    }
}
