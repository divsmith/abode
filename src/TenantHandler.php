<?php namespace Abode;

use Symfony\Component\HttpFoundation\Request;

interface TenantHandler
{
	public function validate(Request $request);

	public function failed();
}