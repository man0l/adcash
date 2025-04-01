<?php

declare(strict_types=1);

namespace App\Tests;

use App\BidProcessor;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class BidProcessorTest extends TestCase
{
    private BidProcessor $processor;

    protected function setUp(): void
    {
        $this->processor = new BidProcessor();
    }

    public static function validFilesProvider(): array
    {
        return [
            'example case' => [
                'file' => __DIR__ . '/../data/example.csv',
                'expectedAdId' => 4,
                'expectedSecondBid' => 33.0
            ],
            'duplicate highest bid' => [
                'file' => __DIR__ . '/../data/duplicate_high.csv',
                'expectedAdId' => 3,
                'expectedSecondBid' => 33.0
            ]
        ];
    }

    #[DataProvider('validFilesProvider')]
    public function testValidFiles(string $file, int $expectedAdId, float $expectedSecondBid): void
    {
        $result = $this->processor->findWinningBid($file);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('adId', $result);
        $this->assertArrayHasKey('secondBid', $result);
        $this->assertEquals($expectedAdId, $result['adId']);
        $this->assertEquals($expectedSecondBid, $result['secondBid']);
    }

    public static function invalidFilesProvider(): array
    {
        return [
            'empty file' => [
                'file' => __DIR__ . '/../data/empty.csv',
                'expectedException' => \RuntimeException::class,
                'expectedMessage' => "Error: No valid bids found in the file"
            ],
            'one bid only' => [
                'file' => __DIR__ . '/../data/one_bid.csv',
                'expectedException' => \RuntimeException::class,
                'expectedMessage' => "Error: Only one valid bid found. Cannot determine second-highest bid price"
            ],
            'non-existent file' => [
                'file' => __DIR__ . '/../data/nonexistent.csv',
                'expectedException' => \InvalidArgumentException::class,
                'expectedMessage' => "Error: File not found at path"
            ]
        ];
    }

    #[DataProvider('invalidFilesProvider')]
    public function testInvalidFiles(string $file, string $expectedException, string $expectedMessage): void
    {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedMessage);
        
        $this->processor->findWinningBid($file);
    }

    public function testInvalidFormatFile(): void
    {
        $result = $this->processor->findWinningBid(__DIR__ . '/../data/invalid_format.csv');
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('adId', $result);
        $this->assertArrayHasKey('secondBid', $result);
        
        $this->assertEquals(3, $result['adId']);
        $this->assertEquals(0.5, $result['secondBid']);
    }
} 