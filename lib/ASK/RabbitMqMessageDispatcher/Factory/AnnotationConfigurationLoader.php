<?php
namespace ASK\RabbitMqMessageDispatcher\Factory;

class AnnotationConfigurationLoader implements ConfigurationLoaderInterface
{
    /**
     * @var ClassLocatorInterface
     */
    protected $classLocator;

    /**
     * @var ConfigurationFactory
     */
    protected $configurationFactory;

    /**
     * @param ClassLocatorInterface $classLocator
     * @param ConfigurationFactory  $configurationFactory
     */
    public function __construct(ClassLocatorInterface $classLocator, ConfigurationFactory $configurationFactory)
    {
        $this->classLocator = $classLocator;
        $this->configurationFactory = $configurationFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $exchanges = [];
        $queues = [];
        $bindings = [];

        foreach ($this->classLocator as $class) {
            if ($config = $this->configurationFactory->getExchange($class)) {
                $exchanges[] = $config;
            }

            if ($config = $this->configurationFactory->getQueue($class)) {
                $queues[] = $config;
            }

            if ($config = $this->configurationFactory->getBinding($class)) {
                $bindings[] = $config;
            }
        }

        return new Configuration($exchanges, $queues, $bindings);
    }
}