<?php

namespace SpiffyUserTest\Authentication;

use SpiffyUser\Authentication\AdapterChain;
use SpiffyUserTest\Asset\ChainAdapter;
use Zend\ServiceManager\ServiceManager;

class AdapterChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AdapterChain
     */
    protected $chain;

    public function setUp()
    {
        $this->chain = new AdapterChain();
    }

    /**
     * @covers \SpiffyUser\Authentication\AdapterChain::getEventManager
     * @covers \SpiffyUser\Authentication\AdapterChain::setEventManager
     */
    public function testGetEventManagerIsLazyLoaded()
    {
        $this->assertInstanceOf('Zend\EventManager\EventManager', $this->chain->getEventManager());
    }

    /**
     * @covers \SpiffyUser\Authentication\AdapterChain::getEvent
     * @covers \SpiffyUser\Authentication\AdapterChain::setEvent
     */
    public function testGetEventIsLazyLoaded()
    {
        $this->assertInstanceOf('SpiffyUser\Authentication\ChainEvent', $this->chain->getEvent());
    }

    /**
     * @covers \SpiffyUser\Authentication\AdapterChain::addAdapter
     * @covers \SpiffyUser\Authentication\AdapterChain::setAdapters
     * @covers \SpiffyUser\Authentication\AdapterChain::getAdapters
     */
    public function testAdapters()
    {
        $adapters = array(
            -1       => new ChainAdapter(),
            10       => new ChainAdapter(),
            'nonint' => new ChainAdapter()
        );

        $this->chain->setAdapters($adapters);
        $this->assertCount(3, $this->chain->getAdapters());

        $em        = $this->chain->getEventManager();
        $listeners = $em->getListeners('test')->toArray();

        $this->assertCount(3, $listeners);

        foreach (array(-1, 10, 100) as $key => $priority) {
            $metadata = $listeners[$key]->getMetadata();
            $this->assertEquals($priority, $metadata['priority']);
        }

        $this->assertEquals(array_values($adapters), $this->chain->getAdapters());
    }

    /**
     * @covers \SpiffyUser\Authentication\AdapterChain::authenticate
     */
    public function testAuthenticate()
    {
        $this->chain->setEventParams(array(
            'foo'    => 'bar',
            'result' => true,
        ));

        $event = $this->chain->getEvent();

        $this->chain->addAdapter(new ChainAdapter());
        $result = $this->chain->authenticate();

        $this->assertTrue($event->getParam('result'));
        $this->assertTrue($result->isValid());
        $this->assertEquals('foo', $result->getIdentity());

        $this->chain->setEventParams(array(
            'result' => false
        ));

        $result = $this->chain->authenticate();
        $this->assertFalse($event->getParam('result'));
        $this->assertFalse($result->isValid());
        $this->assertNull($result->getIdentity());
        $this->assertEquals(array('failure'), $result->getMessages());
    }

    /**
     * @covers \SpiffyUser\Authentication\AdapterChain::setEventParams
     * @covers \SpiffyUser\Authentication\AdapterChain::getEventParams
     */
    public function testParams()
    {
        $expected = array('foo', 'bar');
        $this->chain->setEventParams($expected);
        $this->assertEquals($expected, $this->chain->getEventParams());
    }

    /**
     * @covers \SpiffyUser\Authentication\AdapterChain::clearAdapters
     */
    public function testAdaptersCanBeCleared()
    {
        $this->chain->addAdapter($this->getMockForAbstractClass('SpiffyUser\Authentication\ChainableAdapterInterface'));
        $this->assertSame(1, count($this->chain->getAdapters()));
        $this->chain->clearAdapters();
        $this->assertSame(0, count($this->chain->getAdapters()));
    }
}
