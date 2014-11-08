<?php
namespace ASK\RabbitMqMessageDispatcher\Factory;

use ASK\RabbitMqMessageDispatcher\Metadata\Metadata;
use Metadata\MetadataFactory;

class ConfigurationFactory
{
    /**
     * @var MetadataFactory
     */
    protected $metadataFactory;

    /**
     * @var Namer
     */
    protected $namer;

    /**
     * @var string
     */
    protected $defaultConnection;

    /**
     * @param MetadataFactory $metadataFactory
     * @param Namer           $namer
     * @param string          $defaultConnection
     */
    public function __construct(MetadataFactory $metadataFactory, Namer $namer, $defaultConnection)
    {
        $this->metadataFactory = $metadataFactory;
        $this->namer = $namer;
        $this->defaultConnection = $defaultConnection;
    }

    /**
     * @param $class
     *
     * @return Exchange
     */
    public function getExchange($class)
    {
        /** @var Metadata $metadata */
        if (false == $metadata = $this->metadataFactory->getMetadataForClass($class)) {
            return;
        }

        if (false == $metadata->exchange) {
            return;
        }

        $config = new Exchange();

        if ($metadata->exchangeConnection) {
            $config->setConnection($metadata->exchangeConnection);
        } else {
            $config->setConnection($this->defaultConnection);
        }

        if ($metadata->exchangeName) {
            $config->setExchange($metadata->exchangeName);
        } else {
            $config->setExchange($this->namer->generateNameFromClass($class));
        }

        $config->setDurable($metadata->exchangeDurable);
        $config->setArguments($metadata->exchangeArguments);

        return $config;
    }

    /**
     * @param $class
     *
     * @return Queue
     */
    public function getQueue($class)
    {
        /** @var Metadata $metadata */
        if (false == $metadata = $this->metadataFactory->getMetadataForClass($class)) {
            return;
        }

        if (false == $metadata->queue) {
            return;
        }

        $config = new Queue();

        if ($metadata->queueConnection) {
            $config->setConnection($metadata->queueConnection);
        } else {
            $config->setConnection($this->defaultConnection);
        }

        if ($metadata->queueName) {
            $config->setQueue($metadata->queueName);
        } else {
            $config->setQueue($this->namer->generateNameFromClass($class));
        }

        $config->setDurable($metadata->queueDurable);

        return $config;
    }

    /**
     * @param $class
     *
     * @return Binding
     */
    public function getBinding($class)
    {
        /** @var Metadata $metadata */
        if (false == $metadata = $this->metadataFactory->getMetadataForClass($class)) {
            return;
        }

        if ($metadata->queueBind) {
            return $this->getQueueBinding($metadata);
        }

        if ($metadata->exchangeBind) {
            return $this->getExchangeBinding($metadata);
        }
    }

    protected function getQueueBinding(Metadata $metadata)
    {
        if (false == $dstConfig = $this->getQueue($metadata->name)) {
            throw new \RuntimeException();
        }

        if (false == $srcConfig = $this->getExchange($metadata->queueBind)) {
            throw new \RuntimeException();
        }

        if ($dstConfig->getConnection() !== $srcConfig->getConnection()) {
            throw new \LogicException();
        }

        $config = new Binding();
        $config->setType(Binding::TYPE_QUEUE);
        $config->setConnection($dstConfig->getConnection());
        $config->setDst($dstConfig->getQueue());
        $config->setSrc($srcConfig->getExchange());

        return $config;
    }

    protected function getExchangeBinding(Metadata $metadata)
    {
        if (false == $dstConfig = $this->getExchange($metadata->name)) {
            throw new \RuntimeException();
        }

        if (false == $srcConfig = $this->getExchange($metadata->queueBind)) {
            throw new \RuntimeException();
        }

        if ($dstConfig->getConnection() !== $srcConfig->getConnection()) {
            throw new \LogicException();
        }

        $config = new Binding();
        $config->setType(Binding::TYPE_EXCHANGE);
        $config->setConnection($dstConfig->getConnection());
        $config->setDst($dstConfig->getExchange());
        $config->setSrc($srcConfig->getExchange());

        return $config;
    }
}
