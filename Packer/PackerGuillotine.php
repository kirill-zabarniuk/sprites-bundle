<?php

namespace Fernando\Bundle\SpritesBundle\Packer;

use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

class PackerGuillotine
{
    private $jarPathYml;
    private $jarPath;
    private $javaPath;

    private $defaultOptions = array(
        'padding'   => 0,
        'max-width' => 2000,
    );

    public function __construct($jarPathYml, $jarPath, $javaPath = '/usr/bin/java')
    {
        $this->jarPathYml = $jarPathYml;
        $this->jarPath    = $jarPath;
        $this->javaPath   = $javaPath;
    }

    public function pack($map, $options = array())
    {
//        $cmd = strtr('%java_path% -Djava.awt.headless=true -cp %base%/lib/snakeyaml-1.9-SNAPSHOT.jar -jar %base%/lib/spritetools-1.0.jar', array(
//            '%base%' => __DIR__ . '/../vendor/spritetools'
//        ));
        $cmd = strtr('%java_path% -Djava.awt.headless=true -cp %jar_path_yml% -jar %jar_path%', array(
            '%java_path%'    => $this->javaPath,
            '%jar_path_yml%' => $this->jarPathYml,
            '%jar_path%'     => $this->jarPath,
        ));

        $params = array_merge($this->defaultOptions, $options);
        $params['blocks'] = $map;

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
