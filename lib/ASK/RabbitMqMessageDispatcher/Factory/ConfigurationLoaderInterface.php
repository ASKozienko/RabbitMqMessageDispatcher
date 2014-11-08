<?php
namespace ASK\RabbitMqMessageDispatcher\Factory;

interface ConfigurationLoaderInterface
{
    /**
     * @return Configuration
     */
    public function load();
}
