<?php

namespace SpiffyUserTest\Form;

use Zend\Authentication\Result;
use SpiffyUser\Authentication;
use SpiffyUser\Authentication\AdapterChain;
use SpiffyUser\Authentication\ChainEvent;

class ChainEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChainEvent
     */
    protected $event;

    public function setUp()
    {
        $this->event = new ChainEvent(new AdapterChain());
    }

    /**
     * @covers \SpiffyUser\Authentication\ChainEvent::__construct
     * @covers \SpiffyUser\Authentication\ChainEvent::addMessages
     * @covers \SpiffyUser\Authentication\ChainEvent::setMessages
     * @covers \SpiffyUser\Authentication\ChainEvent::getMessages
     * @covers \SpiffyUser\Authentication\ChainEvent::clearMessages
     */
    public function testMessages()
    {
        $original = array('foo', 'bar');
        $expected = array('baz', 'foo', 'bar');

        $this->event->setMessages($original);
        $this->event->addMessages(array('baz'));
        $this->assertEquals($expected, $this->event->getMessages());

        $this->event->clearMessages();
        $this->assertEmpty($this->event->getMessages());
    }

    /**
     * @covers \SpiffyUser\Authentication\ChainEvent::setIdentity
     * @covers \SpiffyUser\Authentication\ChainEvent::getIdentity
     */
    public function testIdentity()
    {
        $this->event->setIdentity('foo');
        $this->assertEquals('foo', $this->event->getIdentity());
    }

    /**
     * @covers \SpiffyUser\Authentication\ChainEvent::setCode
     * @covers \SpiffyUser\Authentication\ChainEvent::getCode
     */
    public function testCode()
    {
        $this->event->setCode(Result::SUCCESS);
        $this->assertEquals(Result::SUCCESS, $this->event->getCode());
    }
}