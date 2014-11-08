<?php
namespace ASK\RabbitMqMessageDispatcher\Metadata;

use Metadata\MergeableClassMetadata;

class Metadata extends MergeableClassMetadata
{
    /**
     * @var string
     */
    public $exchange;

    /**
     * @var string
     */
    public $exchangeName;

    /**
     * @var string
     */
    public $exchangeConnection;

    /**
     * @var bool
     */
    public $exchangeDurable;

    /**
     * @var array
     */
    public $exchangeArguments;

    /**
     * @var string
     */
    public $exchangeBind;

    /**
     * @var string
     */
    public $queue;

    /**
     * @var string
     */
    public $queueName;

    /**
     * @var string
     */
    public $queueConnection;

    /**
     * @var bool
     */
    public $queueDurable;

    /**
     * @var string
     */
    public $queueBind;

    public function serialize()
    {
        return serialize(array(
            $this->exchange,
            $this->exchangeName,
            $this->exchangeConnection,
            $this->exchangeDurable,
            $this->exchangeArguments,
            $this->exchangeBind,
            $this->queue,
            $this->queueName,
            $this->queueConnection,
            $this->queueDurable,
            $this->queueBind,
            parent::serialize(),
        ));
    }

    public function unserialize($str)
    {
        list(
            $this->exchange,
            $this->exchangeName,
            $this->exchangeConnection,
            $this->exchangeDurable,
            $this->exchangeArguments,
            $this->exchangeBind,
            $this->queue,
            $this->queueName,
            $this->queueConnection,
            $this->queueDurable,
            $this->queueBind,
        $parentStr
        ) = unserialize($str);

        parent::unserialize($parentStr);
    }
}
