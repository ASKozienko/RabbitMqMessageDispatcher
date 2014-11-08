<?php
namespace ASK\RabbitMqMessageDispatcher\Factory;


class Configuration
{
    protected $exchanges;

    protected $queues;

    protected $bindings;

    public function __construct(array $exchanges = [], array $queues = [], array $bindings = [])
    {
        $this->exchanges = $exchanges;
        $this->queues = $queues;
        $this->bindings = $bindings;
    }

    public function getExchanges()
    {
        return $this->exchanges;
    }

    public function getQueues()
    {
        return $this->queues;
    }

    public function getBindings()
    {
        return $this->bindings;
    }
}
