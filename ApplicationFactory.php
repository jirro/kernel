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

use Stack\Builder;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ApplicationFactory
{
    const TYPE_HTTP = 1;

    const TYPE_CONSOLE = 2;

    public function createApplication($type, array $config)
    {
        if ($type === self::TYPE_CONSOLE) {
            $application = new ConsoleApplication($config);
        } else {
            $application = new HttpApplication($config);

            $stack = (new Builder())
                ->push('Stack\Session')
                ->push('Whoops\StackPhp\WhoopsMiddleWare')
            ;

            $application = $stack->resolve($application);
        }

        return $application;
    }
}
