<?php

namespace Abode;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

class Abode implements HttpKernelInterface
{
	protected $app;

	protected $handler;

	public function __construct(HttpKernelInterface $app, TenantHandler $handler)
	{
		$this->app = $app;

		$this->handler = $handler;
	}

	public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
	{
		if ( $this->handler->validate($request))
		{
			return $this->app->handle($request);
		}

		return $this->handler->failed();
	}
}
