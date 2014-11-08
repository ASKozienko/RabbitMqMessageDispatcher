<?php
namespace ASK\RabbitMqMessageDispatcher;

use ASK\RabbitMqMessageDispatcher\Factory\ConfigurationFactory;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class MessageDispatcher
{
    /**
     * @var ConnectionFactory
     */
    protected $connectionFactory;

    /**
     * @var ConfigurationFactory
     */
    protected $configFactory;

    /**
     * @var AMQPChannel[]
     */
    protected $channels = [];

    /**
     * @param ConnectionFactory    $connectionFactory
     * @param ConfigurationFactory $configFactory
     */
    public function __construct(ConnectionFactory $connectionFactory, ConfigurationFactory $configFactory)
    {
        $this->connectionFactory = $connectionFactory;
        $this->configFactory = $configFactory;
    }

    /**
     * @param Message $message
     */
    public function dispatch(Message $message)
    {
        if (false == $exchangeConfig = $this->configFactory->getExchange(get_class($message))) {
            throw new \RuntimeException(sprintf('Exchange is missing for class "%s"', get_class($message)));
        }

        if (false == isset($this->channels[$exchangeConfig->getConnection()])) {
            $this->channels[$exchangeConfig->getConnection()] = $this->connectionFactory->getConnection($exchangeConfig->getConnection())->channel();
        }

        $msg = new AMQPMessage($message->getPayload(), $message->getProperties());
        $this->channels[$exchangeConfig->getConnection()]->basic_publish($msg, $exchangeConfig->getExchange());
    }
}
