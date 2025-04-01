<?php

namespace App\Services;

use App\Services\Cache\CacheInterface;
use App\Services\Cache\DummyCache;

class WordFrequencyService
{
    private string $storageFile;
    private array $wordFrequencies;
    private CacheInterface $cache;
    private bool $isDirty = false;

    public function __construct(
        string $storageFile = 'word_frequencies.json',
        ?CacheInterface $cache = null
    ) {
        $this->storageFile = $storageFile;
        $this->cache = $cache ?? new DummyCache();
        $this->loadFrequencies();
    }

    private function loadFrequencies(): void
    {
        $cachedFrequencies = $this->cache->get('all_frequencies');
     
        if ($cachedFrequencies !== null) {
            $this->wordFrequencies = $cachedFrequencies;
            return;
        }
        
        if (file_exists($this->storageFile)) {
            $content = file_get_contents($this->storageFile);
            $this->wordFrequencies = json_decode($content, true) ?? [];
            
            $this->cache->set('all_frequencies', $this->wordFrequencies, 3600); // Cache for 1 hour
        } else {
            $this->wordFrequencies = [];
        }
    }

    private function saveFrequencies(): void
    {
        if (!$this->isDirty) {
            return;
        }
        
        file_put_contents($this->storageFile, json_encode($this->wordFrequencies));
        
        $this->cache->set('all_frequencies', $this->wordFrequencies, 3600); // Cache for 1 hour
        
        $this->isDirty = false;
    }

    public function processText(string $text): void
    {
        if (empty(trim($text))) {
            throw new \InvalidArgumentException("Text cannot be empty");
        }

        $words = preg_split('/\s+/', strtolower($text), -1, PREG_SPLIT_NO_EMPTY);
        
        $textFrequencies = array_count_values($words);
        
        foreach ($textFrequencies as $word => $count) {
            $cacheKey = 'word_' . $word;
            $currentFreq = $this->cache->get($cacheKey);
            
            if ($currentFreq === null) {
                $currentFreq = $this->wordFrequencies[$word] ?? 0;
            }
            
            $newFreq = $currentFreq + $count;
            $this->wordFrequencies[$word] = $newFreq;
            
            $this->cache->set($cacheKey, $newFreq, 3600); // Cache for 1 hour
        }

        $this->isDirty = true;
        $this->saveFrequencies();
    }

    public function getAllFrequencies(): array
    {
        return $this->wordFrequencies;
    }

    public function getWordFrequency(string $word): int
    {
        $word = strtolower($word);
        
        $cacheKey = 'word_' . $word;
        $cachedFrequency = $this->cache->get($cacheKey);
        
        if ($cachedFrequency !== null) {
            return $cachedFrequency;
        }
        
        $frequency = $this->wordFrequencies[$word] ?? 0;
        
        $this->cache->set($cacheKey, $frequency, 3600); // Cache for 1 hour
        
        return $frequency;
    }
} 