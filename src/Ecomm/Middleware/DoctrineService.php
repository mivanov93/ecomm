<?php
;

/**
 * Slim Framework Doctrine middleware (https://github.com/juliangut/slim-doctrine-middleware)
 * Modified to work as a service, since we want it run before $this->app->run
 * @link https://github.com/juliangut/slim-doctrine-middleware for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/slim-doctrine-middleware/master/LICENSE
 */

namespace Ecomm\Middleware;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\ORM\Mapping\Driver\YamlDriver;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Tools\Setup;
use RuntimeException;
use Slim\Slim;

/**
 * Doctrine handler service.
 */
class DoctrineService  {

    /**
     * @var array
     */
    protected $options = [];
    public $app;

    /**
     * Public constructor.
     *
     * @param Slim            $this->app
     */
    public function __construct(Slim $app)
    {
        $this->app = $app;
    }

    /**
     * Check option availavility
     *
     * @param string $option
     * @return bool
     */
    public function hasOption($option) {
        return isset($this->options[$option]);
    }

    /**
     * Get option value or default if none existent
     *
     * @param string $option
     * @param mixed $default
     * @return mixed
     */
    public function getOption($option, $default = null) {
        return $this->hasOption($option) ? $this->options[$option] : $default;
    }

    /**
     * Set option value
     *
     * @param string $option
     * @param mixed $value
     * @return $this
     */
    public function setOption($option, $value) {
        $this->options[$option] = $value;

        return $this;
    }

    /**
     * @param array $options
     * @param array $defaults
     *
     * @return $this
     */
    public function setOptions(array $options, array $defaults = []) {
        $options = array_merge($defaults, $options);
        $this->options = $options;

        return $this;
    }

    /**
     * Set up Doctrine Entity Manager Slim service
     *
     * @throws RuntimeException
     */
    public function setup() {
        $options = $this->app->config('doctrine');
        if (is_array($options)) {
            $this->setOptions($this->options, $options);
        }

        $proxyDir = $this->getOption('proxy_path');
        $cache = DoctrineCacheFactory::configureCache($this->getOption('cache_driver'));

        $config = Setup::createConfiguration(!!$this->app->config('debug'), $proxyDir, $cache);
        $config->setNamingStrategy(new UnderscoreNamingStrategy());

        $this->setupAnnotationMetadata();

        if (!$this->setupMetadataDriver($config)) {
            throw new RuntimeException('No Metadata Driver defined');
        }
        $config->setAutoGenerateProxyClasses($this->getOption('auto_generate_proxies', true) == true);

        $connection = $this->getOption('connection');

        $this->app->container->singleton(
                'entityManager', function () use ($connection, $config) {
            return EntityManager::create($connection, $config);
        }
        );
    }

    /**
     * Set up annotation metadata
     */
    private function setupAnnotationMetadata() {
        $annotationFiles = $this->getOption('annotation_files');
        if ($annotationFiles) {
            if (!is_array($annotationFiles)) {
                $annotationFiles = [$annotationFiles];
            }

            foreach ($annotationFiles as $file) {
                AnnotationRegistry::registerFile($file);
            }
        }

        $annotationNamespaces = $this->getOption('annotation_namespaces');
        if ($annotationNamespaces) {
            if (!is_array($annotationNamespaces)) {
                $annotationNamespaces = [$annotationNamespaces];
            }

            AnnotationRegistry::registerAutoloadNamespaces($annotationNamespaces);
        }

        $annotationAuloaders = $this->getOption('annotation_autoloaders');
        if ($annotationAuloaders) {
            if (!is_array($annotationAuloaders)) {
                $annotationAuloaders = [$annotationAuloaders];
            }

            foreach ($annotationAuloaders as $autoloader) {
                AnnotationRegistry::registerLoader($autoloader);
            }
        }
    }

    /**
     * Set up annotation metadata
     *
     * @param Configuration $config
     *
     * @return bool
     */
    private function setupMetadataDriver(Configuration &$config) {
        $annotationPaths = $this->getOption('annotation_paths');
        if ($annotationPaths) {
            if (!is_array($annotationPaths)) {
                $annotationPaths = [$annotationPaths];
            }

            $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver($annotationPaths, false));
        }

        $xmlPaths = $this->getOption('xml_paths');
        if ($xmlPaths) {
            if (!is_array($xmlPaths)) {
                $xmlPaths = [$xmlPaths];
            }

            $config->setMetadataDriverImpl(new XmlDriver($xmlPaths));
        }

        $yamlPaths = $this->getOption('yaml_paths');
        if ($yamlPaths) {
            if (!is_array($yamlPaths)) {
                $yamlPaths = [$yamlPaths];
            }

            $config->setMetadataDriverImpl(new YamlDriver($yamlPaths));
        }

        return $annotationPaths || $xmlPaths || $yamlPaths;
    }

}
