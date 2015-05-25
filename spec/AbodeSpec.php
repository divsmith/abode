<?php

namespace spec\Abode;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Abode\ValidatesRequest;
use Abode\HandlesValidationFailure;

class AbodeSpec extends ObjectBehavior
{
	function let(HttpKernelInterface $app, ValidatesRequest $validator, HandlesValidationFailure $handler)
	{
		$this->beConstructedWith($app, $validator, $handler);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Abode\Abode');
    }

    function it_extends_HttpKernelInterface()
    {
    	$this->shouldHaveType('Symfony\Component\HttpKernel\HttpKernelInterface');
    }

    function it_calls_handle_on_next_layer_when_validation_passes(	HttpKernelInterface 		$app, 
    																Request 					$request, 
    																ValidatesRequest 		$validator, 
    																HandlesValidationFailure 	$handler)
    {
    	$this->beConstructedWith($app, $validator, $handler);

    	$validator->validate($request)->willReturn(true);

    	$app->handle($request)->shouldBeCalled();

    	$handler->handle($request)->shouldNotBeCalled();

    	$this->handle($request);
    }

    function it_calls_failed_on_handler_when_validation_fails(  HttpKernelInterface 		$app, 
    															Request 					$request, 
    															ValidatesRequest 		$validator, 
    															HandlesValidationFailure 	$handler)
    {
    	$this->beConstructedWith($app, $validator, $handler);

    	$validator->validate($request)->willReturn(false);

    	$app->handle($request)->shouldNotBeCalled();

    	$handler->handle($request)->shouldBeCalled();

    	$this->handle($request);
    }

    function it_returns_value_from_app_handle_when_validation_passes( 	HttpKernelInterface 		$app, 
    																	Request 					$request, 
    																	ValidatesRequest 		$validator, 
    																	HandlesValidationFailure 	$handler)
    {
    	$this->beConstructedWith($app, $validator, $handler);

    	$validator->validate($request)->willReturn(true);

    	$app->handle($request)->willReturn('success');

    	$this->handle($request)->shouldReturn('success');
    }

    function it_returns_value_from_HandlesValidationFailure_handle_when_validation_passes( 	HttpKernelInterface 		$app, 
    																						Request 					$request, 
    																						ValidatesRequest 		$validator, 
    																						HandlesValidationFailure 	$handler)
    {
    	$this->beConstructedWith($app, $validator, $handler);

    	$validator->validate($request)->willReturn(false);

    	$handler->handle($request)->willReturn('failure');

    	$this->handle($request)->shouldReturn('failure');
    }
}
