<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Controllers\WordFrequencyController;
use App\Services\WordFrequencyService;
use App\Services\Cache\CacheInterface;
use InvalidArgumentException;

class WordFrequencyControllerTest extends TestCase
{
    private $mockService;
    private $controller;
    
    protected function setUp(): void
    {
        $this->mockService = $this->createMock(WordFrequencyService::class);
        $this->controller = new WordFrequencyController($this->mockService);
    }
    
    public function testProcessText(): void
    {
        $text = "hello world hello";
        $input = ['text' => $text];
        $expectedResult = ['status' => 'success', 'message' => 'Text processed successfully'];
        
        $this->mockService->expects($this->once())
            ->method('processText')
            ->with($text);
        
        $result = $this->controller->processText($input);
        
        $this->assertEquals($expectedResult, $result);
    }
    
    public function testGetAllFrequencies(): void
    {
        $frequencies = ['hello' => 2, 'world' => 1];
        
        $this->mockService->expects($this->once())
            ->method('getAllFrequencies')
            ->willReturn($frequencies);
        
        $result = $this->controller->getAllFrequencies();
        
        $this->assertEquals($frequencies, $result);
    }
    
    public function testGetWordFrequency(): void
    {
        $word = 'hello';
        $frequency = 5;
        $expectedResult = ['word' => $word, 'frequency' => $frequency];
        
        $this->mockService->expects($this->once())
            ->method('getWordFrequency')
            ->with($word)
            ->willReturn($frequency);
        
        $result = $this->controller->getWordFrequency($word);
        
        $this->assertEquals($expectedResult, $result);
    }
    
    public function testProcessTextWithMissingInputThrowsException(): void
    {
        $input = []; // Missing 'text' key
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Text field is required');
        $this->controller->processText($input);
    }
} 