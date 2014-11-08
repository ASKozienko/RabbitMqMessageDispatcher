<?php
namespace ASK\RabbitMqMessageDispatcher\Metadata;

use ASK\RabbitMqMessageDispatcher\Annotation\Exchange;
use ASK\RabbitMqMessageDispatcher\Annotation\Queue;
use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;

class AnnotationMetadataDriver implements DriverInterface
{
    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        /** @var Metadata $metadata */
        $metadata = null;
        foreach ($this->reader->getClassAnnotations($class) as $annotation) {
            if ($annotation instanceof Exchange) {
                $metadata = $metadata ?: new Metadata($class->name);

                $metadata->exchange = true;
                $metadata->exchangeConnection = $annotation->connection;
                $metadata->exchangeName = $annotation->name;
                $metadata->exchangeDurable = $annotation->durable;
                $metadata->exchangeArguments = $annotation->arguments;
                $metadata->exchangeBind = $annotation->bind;
            } elseif ($annotation instanceof Queue) {
                $metadata = $metadata ?: new Metadata($class->name);

                $metadata->queue = true;
                $metadata->queueConnection = $annotation->connection;
                $metadata->queueName = $annotation->name;
                $metadata->queueDurable = $annotation->durable;
                $metadata->queueBind = $annotation->bind;
            }
        }

        return $metadata;
    }
}
