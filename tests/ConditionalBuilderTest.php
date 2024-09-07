<?php

namespace StoutLogic\AcfBuilder\Tests;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use PHPUnit\Framework\TestCase;
use StoutLogic\AcfBuilder\ConditionalBuilder;

class ConditionalBuilderTest extends TestCase
{
    use ArraySubsetAsserts;

    public function testContionalLogic()
    {
        $builder = new ConditionalBuilder('color', '==', 'other');

        $expectedConfig = [
            [
                [
                    'field' => 'color',
                    'operator'  =>  '==',
                    'value' => 'other',
                ],
            ]
        ];

        $this->assertArraySubset($expectedConfig, $builder->build());
    }

    public function testContionalLogicOperatorOnly()
    {
        $builder = new ConditionalBuilder('color', '!=empty');

        $expectedConfig = [
            [
                [
                    'field' => 'color',
                    'operator'  =>  '!=empty',
                ],
            ]
        ];

        $this->assertSame($expectedConfig, $builder->build());
    }

    public function testAnd()
    {
        $builder = new ConditionalBuilder('color', '==', 'other');
        $builder->and('number', '!=', '');

        $expectedConfig = [
            [
                [
                    'field' => 'color',
                    'operator'  =>  '==',
                    'value' => 'other',
                ],
                [
                    'field' => 'number',
                    'operator'  =>  '!=',
                    'value' => '',
                ],
            ]
        ];

        $this->assertArraySubset($expectedConfig, $builder->build());
    }

    public function testOr()
    {
        $builder = new ConditionalBuilder('color', '==', 'other');
        $builder->or('number', '>', '5')
                ->and('number', '<', '10')
                ->and('color', '!=', 'other');

        $expectedConfig = [
            [
                [
                    'field' => 'color',
                    'operator'  =>  '==',
                    'value' => 'other',
                ],
            ],
            [
                [
                    'field' => 'number',
                    'operator'  =>  '>',
                    'value' => '5',
                ],
                [
                    'field' => 'number',
                    'operator'  =>  '<',
                    'value' => '10',
                ],
                [
                    'field' => 'color',
                    'operator'  =>  '!=',
                    'value' => 'other',
                ],
            ],
        ];

        $this->assertArraySubset($expectedConfig, $builder->build());
    }
}
