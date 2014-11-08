<?php
namespace ASK\RabbitMqMessageDispatcher\Factory;

use Symfony\Component\Finder\Finder;

class FinderClassLocator implements ClassLocatorInterface
{
    const EXTENSION = 'php';

    /**
     * @var string
     */
    protected $dir;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @var \Iterator
     */
    protected $iterator;

    public function __construct($dir, array $exclude = [])
    {
        $this->dir = $dir;

        $filter = function(\SplFileInfo $file) {
            return class_exists($this->getClassFromFile($file));
        };

        $finder = new Finder();
        $finder
            ->files()
            ->name('*.' . self::EXTENSION)
            ->exclude($exclude)
            ->filter($filter)
            ->in($dir)
        ;

        $this->iterator = $finder->getIterator();
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
