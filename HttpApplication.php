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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class HttpApplication implements HttpKernelInterface
{
    private $container;

    public function __construct(array $config)
    {
        $this->container = new Container();

        $this->initConfig($config);
        $this->initServiceProviders();
    }

    private function initConfig($config)
    {
        $this->container['config'] = function () use ($config) {
            return $config;
        };
    }

    private function initServiceProviders()
    {
        foreach ($this->container->get('config')['services']['http'] as $service) {
            $this->container->addServiceProvider($service);
        }
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $this->container['request'] = $request;

        $route      = $this->container->get('route');
        $dispatcher = $route->getDispatcher();
        $response   = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

        return $response;
    }
}
