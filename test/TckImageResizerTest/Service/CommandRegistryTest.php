<?php
/**
 * Smart image resizing (and manipulation) by url module for Zend Framework 2
 *
 * @link      http://github.com/tck/zf2-imageresizer for the canonical source repository
 * @copyright Copyright (c) 2014 Tobias Knab
 * 
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace TckImageResizerTest\Service;

use TckImageResizer\Service\CommandRegistry;
use TckImageResizer\Exception\BadMethodCallException;
use PHPUnit_Framework_TestCase;

class CommandRegistryTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        CommandRegistry::destroy();
    }
    
    public function testCommandRegistrySingleton()
    {
        CommandRegistry::destroy();
        
        $this->assertInstanceOf('TckImageResizer\Service\CommandRegistry', CommandRegistry::getInstance());
    }
    
    public function testRegisterIsCallable()
    {
        $commandRegistry = CommandRegistry::register('test', function () {});
        
        $this->assertInstanceOf('TckImageResizer\Service\CommandRegistry', $commandRegistry);
    }
    
    /**
     * @expectedException BadMethodCallException
     */
    public function testExceptionRegisterParamCommand()
    {
        CommandRegistry::register('', function () {});
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testExceptionRegisterParamCallback()
    {
        CommandRegistry::register('test', '');
    }
    
    public function testGetCommand()
    {
        CommandRegistry::register('test', function () {});
        
        $command = CommandRegistry::getCommand('test');
        
        $this->assertTrue(is_callable($command));
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testExceptionGetCommandParamWrong()
    {
        CommandRegistry::getCommand('');
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage testname
     */
    public function testExceptionGetCommandParamTestname()
    {
        CommandRegistry::getCommand('testname');
    }
    
    public function testHasCommand()
    {
        CommandRegistry::register('test', function () {});
        
        $this->assertTrue(CommandRegistry::hasCommand('test'));
        $this->assertFalse(CommandRegistry::hasCommand('testabc'));
    }
    
    public function testGetCommands()
    {
        CommandRegistry::register('test1', function () {});
        CommandRegistry::register('test2', function () {});
        
        $commands = CommandRegistry::getCommands();

        $this->assertArrayHasKey('test1', $commands);
        $this->assertArrayHasKey('test2', $commands);
        $this->assertEquals(2, count($commands));
    }
    
}