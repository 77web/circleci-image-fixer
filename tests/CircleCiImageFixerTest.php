<?php

declare(strict_types=1);

namespace Quartetcom\CircleCiImageFixer;

use PHPUnit\Framework\TestCase;

class CircleCiImageFixerTest extends TestCase
{
    /** @var CircleCiImageFixer */
    protected $circleCiImageFixer;

    protected function setUp(): void
    {
        $this->circleCiImageFixer = new CircleCiImageFixer();
    }

    public function testIsInstanceOfCircleCiImageFixer(): void
    {
        $actual = $this->circleCiImageFixer;
        $this->assertInstanceOf(CircleCiImageFixer::class, $actual);
    }
}
