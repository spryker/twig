<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Twig\Extender;

use Codeception\Test\Unit;
use Spryker\Shared\Twig\Extender\UndefinedCallableFallbackExtender;
use Twig\Environment;
use Twig\Error\SyntaxError;
use Twig\Loader\ArrayLoader;
use Twig\TwigFunction;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Twig
 * @group Extender
 * @group UndefinedCallableFallbackExtenderTest
 * Add your own group annotations below this line
 */
class UndefinedCallableFallbackExtenderTest extends Unit
{
    public function testGivenDisabledExtenderWhenTemplateWithUnknownFunctionIsRenderedThenSyntaxErrorIsThrown(): void
    {
        // Arrange
        $twig = (new UndefinedCallableFallbackExtender(false))->extend($this->createEnvironment('{{ unknownFunction("input") }}'));

        // Expect
        $this->expectException(SyntaxError::class);

        // Act
        $twig->render('template');
    }

    public function testGivenUnknownFunctionWithInputWhenTemplateIsRenderedThenInputIsReturned(): void
    {
        // Arrange
        $twig = $this->createExtendedEnvironment('{{ unknownFunction("input", "ignored", named: "ignored") }}');

        // Act
        $output = $twig->render('template');

        // Assert
        $this->assertSame('input', $output);
    }

    public function testGivenUnknownFunctionWithoutInputWhenTemplateIsRenderedThenNullIsReturned(): void
    {
        // Arrange
        $twig = $this->createExtendedEnvironment('{{ unknownFunction() is null ? "null" : "not-null" }}');

        // Act
        $output = $twig->render('template');

        // Assert
        $this->assertSame('null', $output);
    }

    public function testGivenUnknownFunctionInNeverExecutedBranchWhenTemplateIsRenderedThenTemplateCompiles(): void
    {
        // Arrange
        $twig = $this->createExtendedEnvironment('{% if false %}{{ unknownFunction() }}{% endif %}rendered');

        // Act
        $output = $twig->render('template');

        // Assert
        $this->assertSame('rendered', $output);
    }

    public function testGivenUnknownFilterWhenTemplateIsRenderedThenFilterInputIsReturned(): void
    {
        // Arrange
        $twig = $this->createExtendedEnvironment('{{ "input"|unknownFilter("ignored") }}');

        // Act
        $output = $twig->render('template');

        // Assert
        $this->assertSame('input', $output);
    }

    public function testGivenUnknownTestWhenTemplateIsRenderedThenTestEvaluatesToFalse(): void
    {
        // Arrange
        $twig = $this->createExtendedEnvironment('{{ "input" is unknownTest("ignored") ? "true" : "false" }}');

        // Act
        $output = $twig->render('template');

        // Assert
        $this->assertSame('false', $output);
    }

    public function testGivenRegisteredFunctionWhenTemplateIsRenderedThenRegisteredFunctionIsUsed(): void
    {
        // Arrange
        $twig = $this->createEnvironment('{{ registeredFunction("input") }}');
        $twig->addFunction(new TwigFunction('registeredFunction', static fn (string $value): string => strtoupper($value)));
        (new UndefinedCallableFallbackExtender(true))->extend($twig);

        // Act
        $output = $twig->render('template');

        // Assert
        $this->assertSame('INPUT', $output);
    }

    protected function createExtendedEnvironment(string $template): Environment
    {
        return (new UndefinedCallableFallbackExtender(true))->extend($this->createEnvironment($template));
    }

    protected function createEnvironment(string $template): Environment
    {
        return new Environment(new ArrayLoader(['template' => $template]), ['strict_variables' => true]);
    }
}
