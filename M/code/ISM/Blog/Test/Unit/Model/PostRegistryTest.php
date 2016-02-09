<?php

namespace ISM\Blog\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class PostRegistryTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \ISM\Blog\Model\PostFactory|\PHPUnit_Framework_MockObject_MockObject */
    private $postFactory;
    /** @var  \ISM\Blog\Model\PostRegistry */
    private $postRegistry;
    /** @var  \ISM\Blog\Model\Post|\PHPUnit_Framework_MockObject_MockObject */
    private $post;

    CONST POST_ID = 1;
    CONST URL = 'test_url';
    public function setUp()
    {
        $this->postFactory = $this->getMockBuilder('ISM\Blog\Model\PostFactory')
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager = new ObjectManager($this);
        $this->postRegistry = $objectManager->getObject('ISM\Blog\Model\PostRegistry',
            ['postFactory' => $this->postFactory]);
        $this->post = $this->getMockBuilder('ISM\Blog\Model\Post')
            ->disableOriginalConstructor()
            ->setMethods([
                'load',
                'getId',
                'checkUrlKey',
                'getUrlKey',
                '__wakeup'
                ]
            )->getMock();
    }

    public function testRetrieve()
    {
        $this->postFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->post);
        $this->post->expects($this->once())
            ->method('load')
            ->willReturn($this->post);
        $this->post->expects($this->once())
            ->method('getId')
            ->willReturn(self::POST_ID);
        $this->post->expects($this->once())
            ->method('getUrlKey')
            ->willReturn(self::URL);
        $actual = $this->postRegistry->retrieve(self::POST_ID);
        $this->assertSame($this->post, $actual);
        $actualCached = $this->postRegistry->retrieve(self::POST_ID);
        $this->assertSame($this->post, $actualCached);
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testRetrieveException()
    {
        $this->postFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->post);
        $this->post->expects($this->once())
            ->method('load')
            ->willReturn($this->post);
        $this->post->expects($this->once())
            ->method('getId')
            ->willReturn(null);
        $this->postRegistry->retrieve(self::POST_ID);
    }

    public function testRetrieveByUrl()
    {
        $this->post->expects($this->once())
            ->method('checkUrlKey')
            ->willReturn(self::POST_ID);
        $this->post->expects($this->once())
            ->method('load')
            ->with(self::POST_ID)
            ->willReturn($this->post);
        $this->postFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->post);
        $actual = $this->postRegistry->retrieveByUrl(self::URL);
        $this->assertSame($this->post, $actual);
        $actualCached = $this->postRegistry->retrieveByUrl(self::URL);
        $this->assertSame($this->post, $actualCached);
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testRetrieveByUrlWithException()
    {
        $this->postFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->post);;
        $this->post->expects($this->once())
            ->method('checkUrlKey')
            ->willReturn(null);
        $this->postRegistry->retrieveByUrl(self::URL);
    }

    public function testRemove()
    {
        $this->postFactory->expects($this->exactly(2))
            ->method('create')
            ->willReturn($this->post);
        $this->post->expects($this->exactly(2))
            ->method('load')
            ->willReturn($this->post);
        $this->post->expects($this->exactly(3))
            ->method('getId')
            ->willReturn(self::POST_ID);
        $this->post->expects($this->exactly(3))
            ->method('getUrlKey')
            ->willReturn(self::URL);
        $actual = $this->postRegistry->retrieve(self::POST_ID);
        $this->assertSame($this->post, $actual);
        $this->postRegistry->remove(self::POST_ID);
        $actual = $this->postRegistry->retrieve(self::POST_ID);
        $this->assertSame($this->post, $actual);
    }

    public function testRemoveByUrl()
    {
        $this->post->expects($this->exactly(2))
            ->method('checkUrlKey')
            ->willReturn(self::POST_ID);
        $this->post->expects($this->exactly(2))
            ->method('load')
            ->with(self::POST_ID)
            ->willReturn($this->post);
        $this->post->expects($this->once())
            ->method('getId')
            ->willReturn(self::POST_ID);
        $this->postFactory->expects($this->exactly(2))
            ->method('create')
            ->willReturn($this->post);
        $this->post->expects($this->once())
            ->method('getUrlKey')
            ->willReturn(self::URL);
        $actual = $this->postRegistry->retrieveByUrl(self::URL);
        $this->assertSame($this->post, $actual);
        $this->postRegistry->removeByUrl(self::URL);
        $actual = $this->postRegistry->retrieveByUrl(self::URL);
        $this->assertSame($this->post, $actual);
    }
}