<?php

namespace Tests\Unit\Author;

use App\Repositories\Child\RedisRepository;
use App\Repositories\Repository;
use App\Services\Author\DeleteAuthorService;
use Tests\TestCase;

class DeleteAuthorTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test delete author success
     */
    public function test_delete_author_success()
    {
        $mock = \Mockery::mock('overload:'.Repository::class);
        $mockRedis = \Mockery::mock('overload:'.RedisRepository::class);
        $mock->shouldReceive('deleteByCondition')->andReturn(true);
        $mockRedis->shouldReceive('deleteRedisData')->andReturn(true);
        $mock->shouldReceive('delete')->andReturn(true);
        $call = (new DeleteAuthorService(1))->call();
        // echo "\nresult : " . json_encode($call, JSON_PRETTY_PRINT);
        $this->assertEquals(200, $call->status());
    }
}
