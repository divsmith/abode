<?php

namespace Abode;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

class Abode implements HttpKernelInterface
{
	public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
	{

	}
}
