<?php

namespace Abode;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

class Abode implements HttpKernelInterface
{
	protected $app;

	protected $validator;

	protected $handler;

	public function __construct(HttpKernelInterface $app, ValidatesRequest $validator, HandlesValidationFailure $handler)
	{
		$this->app = $app;

		$this->validator = $validator;

		$this->handler = $handler;
	}

	public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
	{
		if ( $this->validator->validate($request))
		{
			return $this->app->handle($request);
		}

		return $this->handler->handle($request);
	}
}
