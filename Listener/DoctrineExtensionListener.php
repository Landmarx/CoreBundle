<?php
namespace Landmarx\Bundle\CoreBundle\Listener;

class DoctrineExtensionListener implements \Symfony\Component\DependencyInjection\ContainerAwareInterface
{
    /**
     * Container
     * @var \Symfony\Component\DependencyInjection\ContainerInterface 
     */
    protected $container;

    /**
     * Set container
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * On late kernel request
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onLateKernelRequest(\Symfony\Component\HttpKernel\Event\GetResponseEvent $event)
    {
        $translatable = $this->container->get('gedmo.listener.translatable');
        $translatable->setTranslatableLocale($event->getRequest()->getLocale());
    }

    /**
     * On kernel request
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onKernelRequest(\Symfony\Component\HttpKernel\Event\GetResponseEvent $event)
    {
        $securityContext = $this->container->get('security.context', ContainerInterface::NULL_ON_INVALID_REFERENCE);
        
        if (null !== $securityContext &&
            null !== $securityContext->getToken() &&
            $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $loggable = $this->container->get('gedmo.listener.loggable');
            $loggable->setUsername($securityContext->getToken()->getUsername());
        }
    }
}
