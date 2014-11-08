<?php
namespace ASK\RabbitMqMessageDispatcher\Factory;

class ClassLocator implements ClassLocatorInterface
{
    const EXTENSION = 'php';

    /**
     * @var string
     */
    protected $dirs;

    protected $iterator;

    /**
     * @param string $dir
     */
    public function __construct($dir)
    {
        if (false == is_dir($dir) || false == is_executable($dir)) {
            throw new \LogicException(sprintf('Dir "%s" is not readable', $dir));
        }

        $this->dir = $dir;

        $filter = function ($current, $key, $iterator) {
            if ($current->getExtension() !== self::EXTENSION) {
                return false;
            }

            if (false == class_exists($this->getClassFromFile($current))) {
                return false;
            }

            return true;
        };

        $this->iterator = new \CallbackFilterIterator(
            new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir),
                \RecursiveIteratorIterator::LEAVES_ONLY),
            $filter
        );
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->getClassFromFile($this->iterator->current());
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->iterator->key();
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return $this->iterator->next();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return $this->iterator->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->iterator->valid();
    }

    /**
     * @param \SplFileInfo $file
     *
     * @return string
     */
    protected function getClassFromFile(\SplFileInfo $file)
    {
        $class = $file->getBasename('.' . self::EXTENSION);
        $namespace = str_replace('/', '\\', str_replace($this->dir, '', $file->getPath()));

        return $namespace . '\\' . $class;
    }
}
