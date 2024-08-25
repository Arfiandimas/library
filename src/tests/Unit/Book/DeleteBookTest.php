<?php

namespace Tests\Unit\Book;

use App\Repositories\Child\RedisRepository;
use App\Repositories\Repository;
use App\Services\Book\DeleteBookService;
use PHPUnit\Framework\TestCase;

class DeleteBookTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test delete book success
     */
    public function test_delete_book_success()
    {
        $mock = \Mockery::mock('overload:'.Repository::class);
        $mockRedis = \Mockery::mock('overload:'.RedisRepository::class);
        $mock->shouldReceive('condition')->andReturn((object)['id' => 1, 'author_id' => 1]);
        $mockRedis->shouldReceive('deleteRedisData')->andReturn(true);
        $mock->shouldReceive('delete')->andReturn(true);
        $call = (new DeleteBookService(1))->call();
        // echo "\nresult : " . json_encode($call, JSON_PRETTY_PRINT);
        $this->assertEquals(200, $call->status());
    }
}
