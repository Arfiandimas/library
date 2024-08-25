<?php

namespace Tests\Unit\Author;

use App\Models\Book;
use App\Repositories\Child\RedisRepository;
use App\Repositories\Repository;
use App\Services\Author\AuthorBookService;
use Tests\TestCase;

class AuthorBookTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->mockData();
    }

    protected function mockData()
    {
        $mockRedis = \Mockery::mock('overload:'.RedisRepository::class);
        $mock = \Mockery::mock('overload:'.Repository::class);

        $data = json_encode([
            [
                "title" => "Filosofi Teras",
                "description" => "Kita memiliki kebiasaan membesar-besarkan kesedihan. Kita tercabik di antara hal-hal masa kini dan hal-hal yang baru terjadi. Pikirlah apakah sudah ada bukti yang pasti mengenai kesusahan masa depan. Karena sering kali kita lebih disusahkan kekhawatiran kita sendiri",
                "publish_date" => "2018-01-01",
                "author_id" => 1
            ]
        ]);
        $mockRedis->shouldReceive('getRedisData')->andReturn($data);

        $data_book = new Book([
            [
                "title" => "Filosofi Teras",
                "description" => "Kita memiliki kebiasaan membesar-besarkan kesedihan. Kita tercabik di antara hal-hal masa kini dan hal-hal yang baru terjadi. Pikirlah apakah sudah ada bukti yang pasti mengenai kesusahan masa depan. Karena sering kali kita lebih disusahkan kekhawatiran kita sendiri",
                "publish_date" => "2018-01-01",
                "author_id" => 1
            ]
        ]);
        $mockRedis->shouldReceive('getRedisData')->andReturn(null);
        $mock->shouldReceive('condition')->andReturn($data_book);
        $mockRedis->shouldReceive('setRedisData')->andReturn(true);
    }

    /**
     * Test get author books success from redis
     */
    public function test_get_author_books_success_from_redis()
    {  
        $call = (new AuthorBookService(1))->call();
        // echo "\nresult : " . json_encode($call, JSON_PRETTY_PRINT);
        $this->assertEquals(200, $call->status());
    }


    /**
     * Test get author books success from database and store redis
     */
    public function test_get_author_books_success_from_database_and_store_redis()
    {   
        $call = (new AuthorBookService(1))->call();
        // echo "\nresult : " . json_encode($call, JSON_PRETTY_PRINT);
        $this->assertEquals(200, $call->status());
    }
}
