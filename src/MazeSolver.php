<?php

namespace App;

class MazeSolver {
     
    private array $queue = [];    
    private array $distance = [];
    private array $maze = [];
    
    private int $startRow;
    private int $startCol;
    private int $endRow;
    private int $endCol;
    
    private array $rowDirections = [-1, 0, 0, 1];
    private array $colDirections = [0, -1, 1, 0];
    
    public function solve(array $maze): int
    {
        $this->maze = $maze;
        $this->initializePositions();
        
        if ($this->hasObstacle($this->startRow, $this->startCol) || 
            $this->hasObstacle($this->endRow, $this->endCol)) {
            return -1;
        }
        
        $this->initializeDistanceMatrix();
        $this->initializeQueue();
        
        return $this->performBreadthFirstSearch();
    }
    
    private function initializePositions(): void
    {
        $this->startRow = count($this->maze) - 1;
        $this->startCol = count($this->maze[0]) - 1;
        $this->endRow = 0;
        $this->endCol = 0;
    }
    
    private function hasObstacle(int $row, int $col): bool
    {
        return $this->maze[$row][$col] == 1;
    }
    
    private function initializeDistanceMatrix(): void
    {
        $this->distance = [];
        for ($i = 0; $i < count($this->maze); $i++) {
            for ($j = 0; $j < count($this->maze[0]); $j++) {
                $this->distance[$i][$j][0] = PHP_INT_MAX;
                $this->distance[$i][$j][1] = PHP_INT_MAX;
            }
        }
        
        $this->distance[$this->startRow][$this->startCol][0] = 1;
    }
    
    private function initializeQueue(): void
    {
        $this->queue = [];
        array_push($this->queue, [$this->startRow, $this->startCol, 0]);
    }
    
    private function performBreadthFirstSearch(): int
    {
        while (!empty($this->queue)) {
            $current = array_shift($this->queue);
            $row = $current[0];
            $col = $current[1];
            $broken = $current[2];
            
            $dist = $this->distance[$row][$col][$broken];
            
            if ($row == $this->endRow && $col == $this->endCol) {
                return $dist;
            }
            
            for ($i = 0; $i < 4; $i++) {
                $newRow = $row + $this->rowDirections[$i];
                $newCol = $col + $this->colDirections[$i];
                
                if ($this->isValidCell($newRow, $newCol)) {
                    $this->processNeighbor($newRow, $newCol, $broken, $dist);
                }
            }
        }
        
        return -1;
    }
    
    private function isValidCell(int $row, int $col): bool
    {
        return $row >= 0 && $row < count($this->maze) && 
               $col >= 0 && $col < count($this->maze[0]);
    }
    
    private function processNeighbor(int $newRow, int $newCol, int $broken, int $dist): void
    {
        if (!$this->hasObstacle($newRow, $newCol)) {
            if ($this->distance[$newRow][$newCol][$broken] > $dist + 1) {
                $this->distance[$newRow][$newCol][$broken] = $dist + 1;
                array_push($this->queue, [$newRow, $newCol, $broken]);
            }
        } elseif ($broken == 0) {
            if ($this->distance[$newRow][$newCol][1] > $dist + 1) {
                $this->distance[$newRow][$newCol][1] = $dist + 1;
                array_push($this->queue, [$newRow, $newCol, 1]);
            }
        }
    }
}