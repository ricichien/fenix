<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;

class CacheTest extends TestCase
{
    public function test_cache_is_storing_and_retrieving()
    {
        Cache::put('test_key', 'ok', 10);

        $this->assertTrue(Cache::has('test_key'));
        $this->assertEquals('ok', Cache::get('test_key'));
    }
}