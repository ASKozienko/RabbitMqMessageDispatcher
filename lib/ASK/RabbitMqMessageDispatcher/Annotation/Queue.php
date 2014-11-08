<?php
namespace ASK\RabbitMqMessageDispatcher\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Queue
{
    /** @var string */
    public $connection;

    /** @var string */
    public $name;

    /** @var boolean */
    public $durable = true;

    /** @var string */
    public $bind;
}
