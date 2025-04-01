<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Services\WordFrequencyService;
use App\Services\Cache\CacheInterface;

class WordFrequencyServiceTest extends TestCase
{
    private $tempFile;
    private $mockCache;
    
    protected function setUp(): void
    {
     
        $this->tempFile = tempnam(sys_get_temp_dir(), 'wfs_test_');
        $this->mockCache = $this->createMock(CacheInterface::class);
    }
    
    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }
    
    public function testConstructorInitializesService(): void
    {     
        $service = new WordFrequencyService($this->tempFile, $this->mockCache);
        $this->assertInstanceOf(WordFrequencyService::class, $service);
        $this->assertEquals([], $service->getAllFrequencies());
    }
    
    public function testLoadFrequenciesFromFile(): void
    {

        $fileData = ['hello' => 3, 'world' => 2];
        file_put_contents($this->tempFile, json_encode($fileData));
        
        $service = new WordFrequencyService($this->tempFile, $this->mockCache);
        
        $this->assertEquals($fileData, $service->getAllFrequencies());
    }
    
    public function testProcessText(): void
    {
        $service = new WordFrequencyService($this->tempFile, $this->mockCache);
        
        $service->processText("hello world hello");
        
        $expectedFrequencies = ['hello' => 2, 'world' => 1];
        $this->assertEquals($expectedFrequencies, $service->getAllFrequencies());
    }
    
    public function testProcessEmptyTextThrowsException(): void
    {
        $service = new WordFrequencyService($this->tempFile, $this->mockCache);
        
        $this->expectException(\InvalidArgumentException::class);
        $service->processText("   ");
    }
    
    public function testGetWordFrequency(): void
    {
        $word = 'hello';
        $frequency = 5;
        $fileData = [$word => $frequency];
        
        file_put_contents($this->tempFile, json_encode($fileData));
        $service = new WordFrequencyService($this->tempFile, $this->mockCache);
        
        $result = $service->getWordFrequency($word);
        
        $this->assertEquals($frequency, $result);
    }
    
    public function testProcessTextUpdatesFrequencies(): void
    {
        $initialData = ['hello' => 1];
        $text = "hello world";
        $expectedData = ['hello' => 2, 'world' => 1];
        
        file_put_contents($this->tempFile, json_encode($initialData));
        $service = new WordFrequencyService($this->tempFile, $this->mockCache);
        
        $service->processText($text);
        
        $this->assertEquals($expectedData, $service->getAllFrequencies());
    }
} 