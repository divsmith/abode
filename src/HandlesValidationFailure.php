<?php namespace Abode;

use Symfony\Component\HttpFoundation\Request;

interface HandlesValidationFailure
{
	public function handle(Request $request);
}