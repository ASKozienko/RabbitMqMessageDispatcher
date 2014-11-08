<?php
namespace ASK\RabbitMqMessageDispatcher\Factory;

class Binding
{
    const TYPE_EXCHANGE = 2;
    const TYPE_QUEUE = 1;

    /**
     * @var string
     */
    protected $connection;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var string
     */
    protected $src;

    /**
     * @var string
     */
    protected $dst;

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
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * @param string $src
     */
    public function setSrc($src)
    {
        $this->src = $src;
    }

    /**
     * @return string
     */
    public function getDst()
    {
        return $this->dst;
    }

    /**
     * @param string $dst
     */
    public function setDst($dst)
    {
        $this->dst = $dst;
    }
}
