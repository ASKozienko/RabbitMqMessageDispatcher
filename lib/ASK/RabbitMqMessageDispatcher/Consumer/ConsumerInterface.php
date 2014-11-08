<?php
namespace ASK\RabbitMqMessageDispatcher\Consumer;

use PhpAmqpLib\Message\AMQPMessage;

interface ConsumerInterface
{
    /**
     * @param AMQPMessage $message
     */
    public function consume(AMQPMessage $message);
}