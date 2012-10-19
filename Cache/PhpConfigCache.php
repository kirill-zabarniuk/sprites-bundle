<?php

namespace Fernando\Bundle\SpritesBundle\Cache;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Loader\Loader;

/**
 * Сохраняет и загружает данные из кэша
 */
class PhpConfigCache extends Loader
{
    private $dir = '';

    /**
     * Construct.
     *
     * @param string $dir The cache directory
     */
    public function __construct($dir)
    {
        $this->dir = rtrim($dir, DIRECTORY_SEPARATOR);
    }

    private function getPath($file)
    {
        return $this->dir . DIRECTORY_SEPARATOR . $file;
    }

    /**
     * Существует ли файл кэша
     * 
     * @param string $file
     * 
     * @return bool
     */
    public function has($file)
    {
        return file_exists($this->getPath($file));
    }

    /**
     * Loads a PHP file.
     *
     * @param mixed  $file A PHP file path
     * @param string $type The resource type
     *
     * @return mixed
     */
    public function load($file, $type = null)
    {
        $path = $this->getPath($file);

        if (!$this->has($file)) {
            throw new \RuntimeException('File not found '.$path);
        }

        return include $path;
    }

    /**
     * Запись инф-ции ф кэш
     * 
     * @param string $file    A PHP file path
     * @param mixed  $content Содержимое
     * @param bool   $debug   Debug
     */
    public function write($file, $content, $debug = false)
    {
        $path = $this->getPath($file);

        $configCache = new ConfigCache($path, $debug);

        if (!$configCache->isFresh()) {
            $configCache->write(sprintf("<?php\n\n// %s\nreturn %s;\n", $path, var_export($content, true)));
        }
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return Boolean true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'php' === pathinfo($resource, PATHINFO_EXTENSION) && (!$type || 'php' === $type);
    }
}
