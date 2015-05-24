<?php

namespace spec\Abode;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AbodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Abode\Abode');
    }
}
