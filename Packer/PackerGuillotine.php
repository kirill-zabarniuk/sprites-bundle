<?php

namespace Fernando\Bundle\SpritesBundle\Packer;

use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

/**
 * Класс отвечающий за расположение изображений на спрайте
 */
class PackerGuillotine implements PackerInterface
{
    private $jarPathYml;
    private $jarPath;
    private $javaPath;

    private $defaultOptions = array(
        'padding'   => 0,
        'max-width' => 2000,
    );

    /**
     * Конструктор
     * 
     * @param string $jarPathYml Путь к snakeyaml.jar
     * @param string $jarPath    Путь к spritetools.jar
     * @param string $javaPath   Путь к java
     */
    public function __construct($jarPathYml, $jarPath, $javaPath = '/usr/bin/java')
    {
        $this->jarPathYml = $jarPathYml;
        $this->jarPath    = $jarPath;
        $this->javaPath   = $javaPath;
    }

    /**
     * Вычисление координат изображений на спрайте
     * 
     * @param array $dimensions Массив с размерами изображений
     * @param array $options    Опции
     * 
     * @return array
     * @throws \RuntimeException
     */
    public function getPositions($dimensions, $options = array())
    {
        $cmd = strtr('%java_path% -Djava.awt.headless=true -cp %jar_path_yml% -jar %jar_path%', array(
            '%java_path%'    => $this->javaPath,
            '%jar_path_yml%' => $this->jarPathYml,
            '%jar_path%'     => $this->jarPath,
        ));

        $params = array_merge($this->defaultOptions, $options);
        $params['blocks'] = $dimensions;

        $descriptorSpec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("pipe", "w"),
        );

        $process = proc_open($cmd, $descriptorSpec, $pipes);
        if (is_resource($process)) {
            $dumper = new Dumper();

            fwrite($pipes[0], $dumper->dump($params, 2));
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $error = stream_get_contents($pipes[2]);
            $exitCode = proc_close($process);

            if (!empty($error)) {
                throw new \RuntimeException($error);
            }

            $parser = new Parser();
            $output = $parser->parse($output);

            return $output['map'];
        } else {
            echo "Error";
        }
    }
}
