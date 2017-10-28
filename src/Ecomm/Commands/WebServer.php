<?php

namespace Ecomm\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of WebServer
 *
 * @author x0r
 */
class WebServer extends Command {

    private $defaultPort;
    private $routerFilePath;
    private $webRootPath;

    public function __construct($name = null, $webRootPath, $routerFilePath, $defaultPort = 8080) {
        $this->routerFilePath = $routerFilePath;
        $this->defaultPort = $defaultPort;
        $this->webRootPath = $webRootPath;
        parent::__construct($name);
    }

    protected function configure() {
        $this
                ->setDescription("Starts the web server")
                ->addArgument('port', InputArgument::OPTIONAL, 'port to listen on', $this->defaultPort)
                ->addArgument('webRootPath', InputArgument::OPTIONAL, 'Web root path', $this->webRootPath)
                ->addArgument('routerFilePath', InputArgument::OPTIONAL, 'Router file path', $this->routerFilePath);
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $port = $input->getArgument('port');
        $dir = $input->getArgument('webRootPath');
        $file = $input->getArgument('routerFilePath');
        exec("php -S 0.0.0.0:{$port} -t {$dir} {$file}");
    }

}
