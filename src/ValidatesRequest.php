<?php namespace Abode;

use Symfony\Component\HttpFoundation\Request;

interface ValidatesRequest
{
	public function validate(Request $request);
}