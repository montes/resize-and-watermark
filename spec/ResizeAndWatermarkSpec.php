<?php

namespace spec\Montesjmm\ResizeAndWatermark;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Illuminate\Contracts\Config\Repository as Config;

class ResizeAndWatermarkSpec extends ObjectBehavior
{

    function let(Config $config)
    {
        $this->beConstructedWith($config);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Montesjmm\ResizeAndWatermark\ResizeAndWatermark');
    }

    function it_sets_watermarks_files()
    {
        $this->setupWatermarksFiles()->shouldReturn(['transparentWatermarkFile' =>false,
            'noTransparentWatermarkFile' => false]);
    }
}
