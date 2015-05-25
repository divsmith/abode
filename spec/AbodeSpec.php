<?php

namespace spec\Abode;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Abode\TenantHandler;

class AbodeSpec extends ObjectBehavior
{
	function let(HttpKernelInterface $app, TenantHandler $handler)
	{
		$this->beConstructedWith($app, $handler);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Abode\Abode');
    }

    function it_extends_HttpKernelInterface()
    {
    	$this->shouldHaveType('Symfony\Component\HttpKernel\HttpKernelInterface');
    }

    function it_calls_handle_on_next_layer_when_validation_passes(HttpKernelInterface $app, Request $request, TenantHandler $handler)
    {
    	$this->beConstructedWith($app, $handler);

    	$handler->validate($request)->willReturn(true);

    	$app->handle($request)->shouldBeCalled();

    	$this->handle($request);
    }

    function it_calls_failed_on_handler_when_validation_fails(HttpKernelInterface $app, Request $request, TenantHandler $handler)
    {
    	$this->beConstructedWith($app, $handler);

    	$handler->validate($request)->willReturn(false);

    	$app->handle($request)->shouldNotBeCalled();

    	$handler->failed()->shouldBeCalled();

    	$this->handle($request);
    }
}
