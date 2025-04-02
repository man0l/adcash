<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Services\WordFrequencyService;
use App\Controllers\WordFrequencyController;
use InvalidArgumentException;

class WordFrequencyControllerTest extends TestCase
{
    private WordFrequencyController $controller;
    private WordFrequencyService|MockObject $mockService;
    
    protected function setUp(): void
    {
        $this->mockService = $this->createMock(WordFrequencyService::class);
        $this->controller = new WordFrequencyController($this->mockService);
    }
    
    public function testProcessText(): void
    {
        $input = ['text' => 'Hello world, hello'];
        $expectedOutput = ['status' => 'success', 'message' => 'Text processed successfully'];
        
        $this->mockService->expects($this->once())
            ->method('processText')
            ->with($input['text']);

        $result = $this->controller->processText($input);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testGetWordFrequency(): void
    {
        $word = 'hello';
        $frequency = 2;
        $expectedOutput = ['word' => $word, 'frequency' => $frequency];

        $this->mockService->expects($this->once())
            ->method('getWordFrequency')
            ->with($word)
            ->willReturn($frequency);

        $result = $this->controller->getWordFrequency($word);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testGetAllFrequencies(): void
    {
        $frequencyData = ['hello' => 2, 'world' => 1];
        
        $this->mockService->expects($this->once())
            ->method('getAllFrequencies')
            ->willReturn($frequencyData);

        $result = $this->controller->getAllFrequencies();
        $this->assertEquals($frequencyData, $result);
    }

    public function testProcessEmptyTextThrowsException(): void
    {
        $input = ['text' => ''];
        
        $this->mockService->expects($this->once())
            ->method('processText')
            ->with($input['text'])
            ->willThrowException(new InvalidArgumentException("Text cannot be empty"));
            
        $this->expectException(InvalidArgumentException::class);
        $this->controller->processText($input);
    }

    public function testGetWordFrequencyThrowsExceptionForNonExistentWord(): void
    {
        $word = 'nonexistent';
        $this->mockService->expects($this->once())
            ->method('getWordFrequency')
            ->with($word)
            ->willThrowException(new \InvalidArgumentException('Word not found'));

        $this->expectException(\InvalidArgumentException::class);
        $this->controller->getWordFrequency($word);
    }
}
