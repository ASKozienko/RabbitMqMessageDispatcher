<?php
namespace ASK\RabbitMqMessageDispatcher;

abstract class Message
{
    /**
     * @var string
     */
    protected $payload;

    /**
     * @var array
     */
    protected $properties;

    /**
     * @param string $payload
     */
    public function __construct($payload = '')
    {
        $this->payload = $payload;

        // @todo make properties more friendly (constants for delivery mode, setters for common options)
        $this->properties = array(
            'content_type' => 'text/plain',
            'delivery_mode' => 2,
        );
    }

    /**
     * @return string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param string $payload
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param array $options
     */
    public function setProperties($options)
    {
        $this->properties = $options;
    }
}
