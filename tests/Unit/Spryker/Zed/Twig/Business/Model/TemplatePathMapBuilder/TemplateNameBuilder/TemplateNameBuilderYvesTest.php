<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilder;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilder\TemplateNameBuilderYves;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Twig
 * @group Business
 * @group Model
 * @group TemplatePathMapBuilder
 * @group TemplateNameBuilder
 * @group TemplateNameBuilderYvesTest
 */
class TemplateNameBuilderYvesTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider pathDataProvider
     *
     * @param string $path
     * @param string $expectedTemplateName
     *
     * @return void
     */
    public function testBuildTemplateName($path, $expectedTemplateName)
    {
        $templateNameBuilder = new TemplateNameBuilderYves();

        $this->assertSame($expectedTemplateName, $templateNameBuilder->buildTemplateName($path));
    }

    /**
     * @return array
     */
    public function pathDataProvider()
    {
        return [
            ['src/Namespace/Yves/Bundle/Theme/theme-name/Controller/index.twig', '@Bundle/Controller/index.twig'],
            ['vendor/spryker/spryker/Bundles/Bundle/src/Namespace/Yves/Bundle/Theme/theme-name/Controller/index.twig', '@Bundle/Controller/index.twig'],
            ['vendor/spryker/bundle/src/Namespace/Yves/Bundle/Theme/theme-name/Controller/index.twig', '@Bundle/Controller/index.twig'],
            ['vendor/spryker/bundle/src/Namespace/Yves/Bundle/Theme/theme-name/Controller/SubDirectory/index.twig', '@Bundle/Controller/SubDirectory/index.twig'],
        ];
    }

}
