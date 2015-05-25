<?php

namespace spec\Abode;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Abode\ValidatesRequest;
use Abode\HandlesValidationFailure;
use Closure;

class AbodeSpec extends ObjectBehavior
{
	function let(HttpKernelInterface $app, ValidatesRequest $validator, HandlesValidationFailure $handler)
	{
        $this->beConstructedThrough('withHttpKernelInterface', [$app, $validator, $handler]);
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
    	$this->beConstructedThrough('withHttpKernelInterface', [$app, $validator, $handler]);

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
    	$this->beConstructedThrough('withHttpKernelInterface', [$app, $validator, $handler]);

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
    	$this->beConstructedThrough('withHttpKernelInterface', [$app, $validator, $handler]);

    	$validator->validate($request)->willReturn(true);

    	$app->handle($request)->willReturn('success');

    	$this->handle($request)->shouldReturn('success');
    }

    function it_returns_value_from_HandlesValidationFailure_handle_when_validation_passes( 	HttpKernelInterface 		$app, 
    																						Request 					$request, 
    																						ValidatesRequest 		$validator, 
    																						HandlesValidationFailure 	$handler)
    {
    	$this->beConstructedThrough('withHttpKernelInterface', [$app, $validator, $handler]);

    	$validator->validate($request)->willReturn(false);

    	$handler->handle($request)->willReturn('failure');

    	$this->handle($request)->shouldReturn('failure');
    }

    function it_allows_a_closure_to_be_used_to_handle_success(HttpKernelInterface       $app, 
                                                              Request                     $request, 
                                                              ValidatesRequest        $validator, 
                                                              HandlesValidationFailure    $handler)
    {
        $response = new Response('success');
        $closure = function($request) use ($request, $response)
        {
            return $response;
        };

        $this->beConstructedThrough('withClosure', [$closure, $validator, $handler]);

        $validator->validate($request)->willReturn(true);
        $app->handle()->shouldNotBeCalled();
        $handler->handle()->shouldNotBeCalled();
    
        $this->handle($request)->shouldReturn($response);
    }

    function it_throws_exception_if_plain_constructor_used_and_no_closure_or_HttpKernelInterface_set_before_handling(Request $request, 
                                                                                                                    ValidatesRequest $validator, 
                                                                                                                    HandlesValidationFailure $handler)
    {
        $this->beConstructedThrough('plain');

        $this->setRequestValidator($validator);
        $this->setFailureHandler($handler);

        $this->shouldThrow('\Exception')->duringHandle($request);
    }

    function it_throws_exception_if_plain_constructor_used_and_no_request_validator_set_before_handling(HttpKernelInterface       $app, 
                                                                                                          Request                     $request,                                                                                                       
                                                                                                          HandlesValidationFailure    $handler)
    {
        $this->beConstructedThrough('plain');

        $this->setHttpKernelInterface($app);
        $this->setFailureHandler($handler);

        $this->shouldThrow('\Exception')->duringHandle($request);
    }

    function it_throws_exception_if_plain_constructor_used_and_no_failure_handler_set_before_handling(HttpKernelInterface       $app, 
                                                                                                          Request                     $request, 
                                                                                                          ValidatesRequest        $validator)
    {
        $this->beConstructedThrough('plain');

        $this->setHttpKernelInterface($app);
        $this->setRequestValidator($validator);

        $this->shouldThrow('\Exception')->duringHandle($request);
    }
}
