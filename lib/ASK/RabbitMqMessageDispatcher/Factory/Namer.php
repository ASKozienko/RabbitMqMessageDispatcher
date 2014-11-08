<?php
namespace ASK\RabbitMqMessageDispatcher\Factory;

class Namer
{
    /**
     * @param string $class
     *
     * @return string
     */
    public function generateNameFromClass($class)
    {
        return str_replace('\\', '.', $class);
    }
}
