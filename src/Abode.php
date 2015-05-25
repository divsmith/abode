<?php

namespace Abode;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Closure;

class Abode implements HttpKernelInterface
{
	protected $app;

	protected $validator;

	protected $handler;

	public static function withHttpKernelInterface(HttpKernelInterface $app, ValidatesRequest $validator, HandlesValidationFailure $handler)
	{
		$abode = new Abode();
		$abode->setHttpKernelInterface($app);
		$abode->setRequestValidator($validator);
		$abode->setFailureHandler($handler);

		return $abode;
	}

	public static function withClosure(Closure $app, ValidatesRequest $validator, HandlesValidationFailure $handler)
	{
		$abode = new Abode();
		$abode->setClosure($app);
		$abode->setRequestValidator($validator);
		$abode->setFailureHandler($handler);

		return $abode;
	}

	public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
	{
		if ( ! $this->app || ! $this->validator || ! $this->handler )
		{
			throw new \Exception('All required inputs not set');
		}

		if ( $this->validator->validate($request))
		{
			if ( is_callable($this->app))
			{
				return call_user_func($this->app, $request);
			}
			return $this->app->handle($request);
		}

		return $this->handler->handle($request);
	}

    public static function plain()
    {
        return new Abode();
    }

    public function setHttpKernelInterface(HttpKernelInterface $app)
    {
    	$this->app = $app;
    }

    public function setClosure(Closure $closure)
    {
    	$this->app = $closure;
    }

    public function setRequestValidator(ValidatesRequest $validator)
    {
    	$this->validator = $validator;
    }

    public function setFailureHandler(HandlesValidationFailure $handler)
    {
    	$this->handler = $handler;
    }
}
