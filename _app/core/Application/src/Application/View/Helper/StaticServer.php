<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class StaticServer extends AbstractHelper
{
    protected $config = array();

    protected $lastInvokedNamespace;

    public function __construct($conf = null)
    {
        if ($conf !== null) {
            $this->setConfig($conf);
        }
    }

    /**
     * call interfaces :
     *  1) get path from config if exists:
     *     !! if not use or none existance config
     *     previous invoked namespace path will be returned.
     *     (default namespace path is basePath())
     *       $this->staticServer('prefix\namespace');
     *
     *  2) reset path to basePath() system default
     *       $this->staticServer(false);
     *
     *  3) use filename
     *      use previous invoked namespace path and combine filename to that
     *       $this->staticServer('filepath\to\file.ext');
     *
     *  * you can use filepath on 2nd argument on (1) and (2) form
     *       $this->staticServer(false, 'filepath\to\file.ext');
     *       $this->staticServer('prefix\namespace', 'filepath\to\file.ext');
     *
     * @return string
     */
    public function __invoke()
    {
        $file = null;

        $arguments = func_get_args();
        if (count($arguments)>0)
        {
            $arg = array_shift($arguments);
            if ($arg === null || $arg === false || $arg === '') {
                // (2) reset namespace to basepath
                $namespace = false;
                $this->lastInvokedNamespace = $namespace;
            } else {
                if (isset($this->config[$arg])) {
                    $namespace = $arg;
                } else {
                    $filename = (basename($arg));

                    // check that argument is a file by assumming each string having "." inside is file
                    $file = (strstr($filename,'.')) ? $arg : null;

                    $namespace = $this->lastInvokedNamespace;
                }
            }

            $file = (count($arguments)>0 && !$file)
                  ? array_shift($arguments)
                  : ( ($file) ? $file : null );

        } else {
            // (1)
            $namespace = $this->lastInvokedNamespace;
        }

        if (null !== $file) {
            $file = '/' . ltrim($file, '/');
        }

        if (isset($this->config[$namespace])) {
            $path = $this->config[$namespace];
            $this->lastInvokedNamespace = $namespace;
        } else {
            $path = $this->getView()->basePath();
        }

        return rtrim($path,'/').$file;
    }


    protected function setConfig($conf)
    {
        if (!is_array($conf)) {
            throw new \Exception('Invalid parameter for configs');
        }

        $this->config = $conf;
    }
}
