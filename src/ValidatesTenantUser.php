<?php namespace Abode;

use Symfony\Component\HttpFoundation\Request;

interface ValidatesTenantUser
{
	public function validate(Request $request);
}