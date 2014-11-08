<?php
namespace ASK\RabbitMqMessageDispatcher\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Exchange
{
    /** @var string */
    public $connection;

    /** @var string */
    public $name;

    /** @var boolean */
    public $durable = true;

    /** @var array */
    public $arguments;

    /** @var string */
    public $bind;
}
