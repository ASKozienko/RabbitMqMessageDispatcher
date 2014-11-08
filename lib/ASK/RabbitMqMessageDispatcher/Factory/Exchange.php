<?php
namespace ASK\RabbitMqMessageDispatcher\Factory;

class Exchange
{
    /**
     * @var string
     */
    protected $exchange;

    /**
     * @var string
     */
    protected $connection;

    /**
     * @var bool
     */
    protected $durable;

    /**
     * @var array
     */
    protected $arguments;

    /**
     * @return string
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * @param string $exchange
     */
    public function setExchange($exchange)
    {
        $this->exchange = $exchange;
    }

    /**
     * @return string
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param string $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return boolean
     */
    public function isDurable()
    {
        return $this->durable;
    }

    /**
     * @param boolean $durable
     */
    public function setDurable($durable)
    {
        $this->durable = (bool) $durable;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments = null)
    {
        $this->arguments = $arguments;
    }
}
