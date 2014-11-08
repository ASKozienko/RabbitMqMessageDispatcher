<?php
namespace ASK\RabbitMqMessageDispatcher\Consumer;

use ASK\RabbitMqMessageDispatcher\ConnectionFactory;
use ASK\RabbitMqMessageDispatcher\Consumer\Event\ErrorEvent;
use ASK\RabbitMqMessageDispatcher\Consumer\Event\Event;
use ASK\RabbitMqMessageDispatcher\Consumer\Event\Events;
use ASK\RabbitMqMessageDispatcher\Factory\ConfigurationFactory;
use ASK\RabbitMqMessageDispatcher\Factory\Queue;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Subscriber
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
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

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
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    public function subscribe(ConsumerInterface $consumer)
    {
        /** @var Queue $queueConfig */
        if (false == $queueConfig = $this->configFactory->getQueue(get_class($consumer))) {
            throw new \RuntimeException();
        }

        $channel = $this->connectionFactory->getConnection($queueConfig->getConnection())->channel();

        $channel->basic_consume($queueConfig->getQueue(), '', false, false, false, false, function(AMQPMessage $message) use ($consumer) {
            $this->consumeMessage($message, $consumer);
        });

        while (count($channel->callbacks)) {
            $channel->wait();
        }
    }

    protected function consumeMessage(AMQPMessage $message, ConsumerInterface $consumer)
    {
        try {
            $this->dispatchEvent(Events::MESSAGE, $event = new Event($message));

            $consumer->consume($message);
            $this->ackMessage($message);

            $this->dispatchEvent(Events::MESSAGE_SUCCESS, $event);
        } catch (\Exception $e) {
            $this->dispatchEvent(Events::MESSAGE_ERROR, $event = new ErrorEvent($message, $e));

            if ($event->isRequeue()) {
                $this->requeueMessage($message);
                $this->dispatchEvent(Events::MESSAGE_REQUEUE, $event);
            } else {
                $this->dropMessage($message);
                $this->dispatchEvent(Events::MESSAGE_DROP, $event);
            }
        }
    }

    /**
     * @param AMQPMessage $message
     */
    protected function ackMessage(AMQPMessage $message)
    {
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    }

    /**
     * @param AMQPMessage $message
     */
    protected function requeueMessage(AMQPMessage $message)
    {
        $message->delivery_info['channel']->basic_reject($message->delivery_info['delivery_tag'], true);
    }

    /**
     * @param AMQPMessage $message
     */
    protected function dropMessage(AMQPMessage $message)
    {
        $message->delivery_info['channel']->basic_reject($message->delivery_info['delivery_tag'], false);
    }

    /**
     * @param string $eventName
     * @param Event  $event
     */
    protected function dispatchEvent($eventName, Event $event)
    {
        if ($this->eventDispatcher) {
            $this->eventDispatcher->dispatch($eventName, $event);
        }
    }
}
