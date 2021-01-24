<?php
/**
 * Smart image resizing (and manipulation) by url module for Laminas
 *
 * @link      http://github.com/tck/zf2-imageresizer for the canonical source repository
 * @copyright Copyright (c) 2017 Tobias Knab
 * 
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace TckImageResizerTest\Service;

use TckImageResizer\Service\CommandRegistry;
use TckImageResizer\Exception\BadMethodCallException;
use PHPUnit\Framework\TestCase;

class CommandRegistryTest extends TestCase
{
    protected function setUp(): void
    {
        CommandRegistry::destroy();
    }
    
    public function testCommandRegistrySingleton()
    {
        CommandRegistry::destroy();

        self::assertInstanceOf('TckImageResizer\Service\CommandRegistry', CommandRegistry::getInstance());
    }
    
    public function testRegisterIsCallable()
    {
        $commandRegistry = CommandRegistry::register('test', function () {
        });

        self::assertInstanceOf('TckImageResizer\Service\CommandRegistry', $commandRegistry);
    }

    public function testExceptionRegisterParamCommand()
    {
        $this->expectException(\TckImageResizer\Exception\BadMethodCallException::class);

        CommandRegistry::register('', function () {
        });
    }

    public function testExceptionRegisterParamCallback()
    {
        $this->expectException(\TckImageResizer\Exception\BadMethodCallException::class);

        CommandRegistry::register('test', '');
    }
    
    public function testGetCommand()
    {
        CommandRegistry::register('test', function () {
        });
        
        $command = CommandRegistry::getCommand('test');

        self::assertTrue(is_callable($command));
    }

    public function testExceptionGetCommandParamWrong()
    {
        $this->expectException(\TckImageResizer\Exception\BadMethodCallException::class);

        CommandRegistry::getCommand('');
    }

    public function testExceptionGetCommandParamTestname()
    {
        $this->expectException(\TckImageResizer\Exception\BadMethodCallException::class);
        $this->expectExceptionMessageMatches('!testname!');

        CommandRegistry::getCommand('testname');
    }
    
    public function testHasCommand()
    {
        CommandRegistry::register('test', function () {
        });

        self::assertTrue(CommandRegistry::hasCommand('test'));
        self::assertFalse(CommandRegistry::hasCommand('testabc'));
    }

    public function testExceptionHasCommandParamWrong()
    {
        $this->expectException(\TckImageResizer\Exception\BadMethodCallException::class);

        CommandRegistry::hasCommand('');
    }
    
    public function testGetCommands()
    {
        CommandRegistry::register('test1', function () {
        });
        CommandRegistry::register('test2', function () {
        });
        
        $commands = CommandRegistry::getCommands();

        self::assertArrayHasKey('test1', $commands);
        self::assertArrayHasKey('test2', $commands);
        self::assertEquals(2, count($commands));
    }
}
