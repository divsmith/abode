<?php namespace Abode;

use Symfony\Component\HttpFoundation\Request;

interface TenantValidator
{
	public function validate(Request $request);
}