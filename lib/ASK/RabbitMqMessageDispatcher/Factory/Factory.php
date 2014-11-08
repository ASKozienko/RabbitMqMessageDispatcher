<?php
namespace ASK\RabbitMqMessageDispatcher\Factory;

use ASK\RabbitMqMessageDispatcher\ConnectionFactory;

class Factory
{
    /**
     * @var ConfigurationLoaderInterface
     */
    protected $configLoader;

    /**
     * @var ConnectionFactory
     */
    protected $connectionFactory;

    /**
     * @param ConfigurationLoaderInterface $configLoader
     * @param ConnectionFactory $connectionFactory
     */
    public function __construct(ConfigurationLoaderInterface $configLoader, ConnectionFactory $connectionFactory)
    {
        $this->configLoader = $configLoader;
        $this->connectionFactory = $connectionFactory;
    }

    public function create($forceExchange = false, $forceQueue = false)
    {
        $config = $this->configLoader->load();

        foreach ($config->getExchanges() as $exchange) {
            $this->declareExchange($exchange, $forceExchange);
        }

        foreach ($config->getQueues() as $queue) {
            $this->declareQueue($queue, $forceQueue);
        }

        foreach ($config->getBindings() as $binding) {
            $this->bind($binding);
        }
    }

    /**
     * @param Exchange $config
     * @param bool     $force
     */
    protected function declareExchange(Exchange $config, $force)
    {
        $channel = $this->connectionFactory->getConnection($config->getConnection())->channel();

        if ($force) {
            $channel->exchange_delete($config->getExchange());
        }

        $channel->exchange_declare(
            $config->getExchange(),
            'fanout',
            false,
            $config->isDurable(),
            false,
            false,
            false,
            $config->getArguments()
        );
    }

    protected function declareQueue(Queue $config, $force)
    {
        $channel = $this->connectionFactory->getConnection($config->getConnection())->channel();

        if ($force) {
            $channel->queue_delete($config->getQueue());
        }

        $channel->queue_declare($config->getQueue(), false, $config->isDurable(), false, false);
    }

    protected function bind(Binding $config)
    {
        $channel = $this->connectionFactory->getConnection($config->getConnection())->channel();

        if (Binding::TYPE_QUEUE === $config->getType()) {
            $channel->queue_bind($config->getDst(), $config->getSrc());
        } else {
            $channel->exchange_bind($config->getDst(), $config->getSrc());
        }
    }
}
