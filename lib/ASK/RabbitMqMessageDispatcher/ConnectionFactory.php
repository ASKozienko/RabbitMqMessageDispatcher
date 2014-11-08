<?php
namespace ASK\RabbitMqMessageDispatcher;

use PhpAmqpLib\Connection\AbstractConnection;

class ConnectionFactory
{
    /**
     * @var AbstractConnection[]
     */
    protected $connections;

    /**
     * @param string $name
     *
     * @return AbstractConnection
     */
    public function getConnection($name)
    {
        if (false == isset($this->connections[$name])) {
            throw new \LogicException(sprintf('Unknown connection "%s"', $name));
        }

        return $this->connections[$name];
    }

    /**
     * @param string             $name
     * @param AbstractConnection $connection
     */
    public function addConnection($name, AbstractConnection $connection)
    {
        if (isset($this->connections[$name])) {
            throw new \LogicException();
        }

        $this->connections[$name] = $connection;
    }
}
