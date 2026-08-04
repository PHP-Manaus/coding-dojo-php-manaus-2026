<?php

declare(strict_types=1);

namespace Dojo\Tests;

use Dojo\Calculadora;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AmbienteTest extends TestCase
{
    #[Test]
    public function o_autoload_enxerga_a_classe_calculadora(): void
    {
        $this->assertTrue(class_exists(Calculadora::class));
    }
}
