<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Kernel;

use League\Container\Container;
use Symfony\Component\Console\Application;

class ConsoleApplication extends Application
{
    private $container;

    public function __construct(array $config)
    {
        $this->container = new Container();

        $this->initConfig($config);
        $this->initServiceProviders();

        parent::__construct();

        $helpCommand = $this->find('help');
        if ($helpCommand) {
            $this->setDefaultCommand($helpCommand->getName());
        }
    }

    private function initConfig($config)
    {
        $this->container['config'] = function () use ($config) {
            return $config;
        };
    }

    private function initServiceProviders()
    {
        foreach ($container->get('config')['services']['console'] as $service) {
            $container->addServiceProvider($service);
        }
    }

    public function getContainer()
    {
        return $this->container;
    }
}
