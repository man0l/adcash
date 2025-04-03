<?php

declare(strict_types=1);

namespace Tests;

use App\MazeSolver;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class MazeSolverTest extends TestCase
{
    private MazeSolver $solver;

    protected function setUp(): void
    {
        $this->solver = new MazeSolver();
    }
    
    /**
     * Data provider for maze test cases
     * 
     * @return array Array of test cases
     */
    public static function provideMazes(): array
    {
        return [
            'Test case 1: 6x6 maze' => [
                [
                    [0, 0, 0, 0, 0, 0],
                    [1, 1, 1, 1, 1, 0],
                    [0, 0, 0, 0, 0, 0],
                    [0, 1, 1, 1, 1, 1],
                    [0, 1, 1, 1, 1, 1],
                    [0, 0, 0, 0, 0, 0],
                ],
                11
            ],
            'Test case 2: 4x4 maze' => [
                [
                    [0, 1, 1, 0],
                    [0, 0, 0, 1],
                    [1, 1, 0, 0],
                    [1, 1, 1, 0]
                ],
                7
            ],
        ];
    }           
    
    #[DataProvider('provideMazes')]
    public function testSolve(array $maze, int $expected): void
    {
        $this->assertEquals($expected, $this->solver->solve($maze));
    }
}