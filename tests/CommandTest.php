<?php

namespace DrewM\SlimCommander;

use PHPUnit\Framework\TestCase;

use DI\ContainerBuilder;
use Slim\App;

class CommandTest extends TestCase
{
    protected $settings;

    /* @var $app App */
    protected $app;
    protected $container;

    protected function setUp()
    {
        $this->settings = [
            'settings' => [

            ]
        ];

        // Instancia container 
        // Container PHP-DI 
        $containerBuilder = new ContainerBuilder();

        $containerBuilder->addDefinitions( [
            // Disponibiliza configurações no container
            'TestCommand' => function ($c){
                return new Command($c);
            }
        ]);

        $container = $containerBuilder->build();
        $this->container = $container;

        $this->app = new \DrewM\SlimCommander\App($container);
    }

    public function testShouldBeTrue()
    {
        $this->assertTrue(true);
    }

    public function testBasicCommand()
    {
        $this->app->command('T1', 'TestCommand:t1', []);

        try {
            $result = $this->app->run([
                null,
                'T1'
            ]);
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }

        $this->assertTrue($result);
    }

    public function testNamedArg()
    {
        $this->app->command('T2', 'TestCommand:t2', [
            'name'
        ]);

        try {
            $result = $this->app->run([
                null,
                'T2',
                'Drew'
            ]);
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }

        $this->assertEquals('Drew', $result);
    }

    public function testNumberedArg()
    {
        $this->app->command('T3', 'TestCommand:t3', []);

        try {
            $result = $this->app->run([
                null,
                'T3',
                'Drew'
            ]);

        } catch (\Exception $e) {
            $result = $e->getMessage();
        }

        $this->assertEquals('Drew', $result);
    }

    public function testMixedArgs()
    {
        $this->app->command('T4', 'TestCommand:t4', [
            'name'
        ]);

        try {
            $result = $this->app->run([
                null,
                'T4',
                'Drew',
                'Mango'
            ]);

        } catch (\Exception $e) {
            $result = $e->getMessage();
        }

        $this->assertEquals('Mango', $result);
    }

}
