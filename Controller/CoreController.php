<?php
namespace Landmarx\Bundle\CoreBundle\Controller;

use \Symfony\Component\DependencyInjection\ContainerInterface;
use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use \Symfony\Component\Config\FileLocator;
use \Symfony\Component\Yaml\Yaml;
use \Doctrine\Common\Collections\ArrayCollection;

class CoreController extends \FOS\RestBundle\Controller\FOSRestController implements ContainerAwareInterface
{
    /**
     * configuration settings
     * @var ArrayCollection
     */
    private $configs;
    
    /**
     * User IP info
     * @var mixed
     */
    protected $ipinfo;

    /**
     * config file locator
     * @var FileLocater
     */
    private $locator;

    /**
     * container
     * @var type
     */
    protected $container;

    /**
     * security context
     * @var SecurityContext
     */
    protected $securityContext;
    
    /**
     * Document object
     * @var mixed
     */
    protected $document;
    
    /**
     * Repository object
     * @var mixed
     */
    protected $repository;
    
    /**
     * breadcrumb array
     * @var array 
     */
    protected $breadcrumbs;

    /**
     * set container
     * @param type $container container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        
        $this->container = $container;

        $this->securityContext = $this->container->get('security.context');
        
        $this->generateBreadcrumbs();
    }

    /**
     * __construct
     */
    public function __construct()
    {
        $path = __DIR__ .DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' .
                DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
                'app' . DIRECTORY_SEPARATOR;
        $configDirectories = array(
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'config',
            $path . 'config'
        );
        $this->locator = new FileLocator($configDirectories);

        $this->breadcrumbs = array();
    }

    /**
     * Get configuration
     * @return mixed
     */
    public function getConfiguration()
    {
        return $this->config;
    }

    /**
     * Get configuration file parameter
     * @param string $parameter
     * @return type
     */
    public function getParameter($parameter)
    {
        if (!$this->configs instanceof ArrayCollection) {
            $this->generateConfigs('parameters.yml', null, false);
        }

        return $this->configs->get($parameter);
    }

    /**
     * Generate configuration array
     * @param string $file
     * @param string $path
     * @param bool $retFile
     */
    private function generateConfigs($file, $path = null, $retFile = false)
    {
        $configs = Yaml::parse($this->locator->locate($file, $path, $retFile)[0]);

        $this->configs = new ArrayCollection($configs['parameters']);
    }
    
    /**
     * Generate breadcrumbs
     */
    public function generateBreadcrumbs()
    {
        $this->breadcrumbs = $this->get('white_october_breadcrumbs');
        
        $this->breadcrumbs->addItem(
            'dashboard',
            $this->get('router')->generate('homepage')
        );

    }
    
    /**
     * @inheritdoc
     */
    public function updateAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        list($class, $tmp) = explode('Controller', class_name($this), 1);
        $this->breadcrumbs->add($this->get('landmarx.core.inflector')->pluralize($class));
        $this->breadcrumbs->add('update');
        
        
        return parent::showAction($request);
    }
    
    /**
     * @inheritdoc
     */
    public function deleteAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        list($class, $tmp) = explode('Controller', class_name($this), 1);
        $this->breadcrumbs->add($this->get('landmarx.core.inflector')->pluralize($class));
        $this->breadcrumbs->add('delete');
        
        return parent::showAction($request);
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->get('doctrine_mongodb')
        ->getRepository($this->repository);
    }

    /**
     * Get current user session coordinates based on ip address
     * 
     * @return array
     */
    public function getCurrentCoordinates()
    {
        $current = $this->ipinfo['ipinfo']['Location'];
        
        if (!is_array($current)) {
            $current = array(
                'latitude' => 43.754419,
                'longitude' => -70.409296);
            $this->get('session')
                ->getFlashBag()
                ->add(
                    'warning',
                    'your location could not accurately be determined. Default coordinates have been used.'
                );
        }
        
        return $current;
    }
}
