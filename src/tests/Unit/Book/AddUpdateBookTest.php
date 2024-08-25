<?php

namespace Tests\Unit\Book;

use App\Repositories\Child\RedisRepository;
use App\Repositories\Repository;
use App\Services\Book\AddUpdateBookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidatorInstance;
use Tests\TestCase;

class AddUpdateBookTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test store book success
     */
    public function test_store_book_success()
    {
        // Mock the Validator facade
        Validator::shouldReceive('make')
            ->twice()
            ->andReturn(new class extends ValidatorInstance {
                public function __construct() {}
                public function fails()
                {
                    return false; // Simulate that the validation success
                }
                public function validated()
                {
                    return [
                        "title" => "Filosofi Teras",
                        "description" => "Kita memiliki kebiasaan membesar-besarkan kesedihan. Kita tercabik di antara hal-hal masa kini dan hal-hal yang baru terjadi. Pikirlah apakah sudah ada bukti yang pasti mengenai kesusahan masa depan. Karena sering kali kita lebih disusahkan kekhawatiran kita sendiri",
                        "publish_date" => "2018-01-01",
                        "author_id" => 1
                    ];
                }
                public function errors()
                {
                    // Simulate an error message returned by validation
                    return collect([]);
                }
            });
        $mock = \Mockery::mock('overload:'.Repository::class);
        $mockRedis = \Mockery::mock('overload:'.RedisRepository::class);
        $data = [
            "id" => 1,
            "title" => "Filosofi Teras",
            "description" => "Kita memiliki kebiasaan membesar-besarkan kesedihan. Kita tercabik di antara hal-hal masa kini dan hal-hal yang baru terjadi. Pikirlah apakah sudah ada bukti yang pasti mengenai kesusahan masa depan. Karena sering kali kita lebih disusahkan kekhawatiran kita sendiri",
            "publish_date" => "2018-01-01",
            "author_id" => 1
        ];
        $mock->shouldReceive('store')->andReturn((object)$data);
        $mockRedis->shouldReceive('deleteRedisData')->andReturn(true);
        unset($data['id']);
        $call = (new AddUpdateBookService(new Request($data)))->call();
        // echo "\nresult : " . json_encode($call, JSON_PRETTY_PRINT);
        $this->assertEquals(200, $call->status());
    }

    /**
     * Test store book failed
     */
    public function test_store_book_failed()
    {
        // Mock the Validator facade
        Validator::shouldReceive('make')
            ->times(3)
            ->andReturn(new class extends ValidatorInstance {
                public function __construct() {}
                public function fails()
                {
                    return true; // Simulate that the validation fails
                }
                public function validated()
                {
                    return [
                        "title" => "Filosofi Teras",
                        "description" => "Kita memiliki kebiasaan membesar-besarkan kesedihan. Kita tercabik di antara hal-hal masa kini dan hal-hal yang baru terjadi. Pikirlah apakah sudah ada bukti yang pasti mengenai kesusahan masa depan. Karena sering kali kita lebih disusahkan kekhawatiran kita sendiri",
                        "publish_date" => "2018-01-01 10:10:10",
                        "author_id" => 1
                    ];
                }
                public function errors()
                {
                    // Simulate an error message returned by validation
                    return collect(['The selected author_id is invalid.']);
                }
            });
        $mock = \Mockery::mock('overload:'.Repository::class);
        $mockRedis = \Mockery::mock('overload:'.RedisRepository::class);
        $data = [
            "id" => 1,
            "title" => "Filosofi Teras",
            "description" => "Kita memiliki kebiasaan membesar-besarkan kesedihan. Kita tercabik di antara hal-hal masa kini dan hal-hal yang baru terjadi. Pikirlah apakah sudah ada bukti yang pasti mengenai kesusahan masa depan. Karena sering kali kita lebih disusahkan kekhawatiran kita sendiri",
            "publish_date" => "2018-01-01 10:10:10",
            "author_id" => 10
        ];
        $mock->shouldReceive('store')->andReturn((object)$data);
        $mockRedis->shouldReceive('deleteRedisData')->andReturn(true);
        unset($data['id']);
        $call = (new AddUpdateBookService(new Request($data)))->call();
        // echo "\nresult : " . json_encode($call, JSON_PRETTY_PRINT);
        $this->assertEquals(400, $call->status());
    }

    /**
     * Test update book success
     */
    public function test_update_book_success()
    {
        // Mock the Validator facade
        Validator::shouldReceive('make')
            ->twice()
            ->andReturn(new class extends ValidatorInstance {
                public function __construct() {}
                public function fails()
                {
                    return false; // Simulate that the validation success
                }
                public function validated()
                {
                    return [
                        "title" => "Filosofi Teras",
                        "description" => "Kita memiliki kebiasaan membesar-besarkan kesedihan. Kita tercabik di antara hal-hal masa kini dan hal-hal yang baru terjadi. Pikirlah apakah sudah ada bukti yang pasti mengenai kesusahan masa depan. Karena sering kali kita lebih disusahkan kekhawatiran kita sendiri",
                        "publish_date" => "2018-01-01",
                        "author_id" => 1
                    ];
                }
                public function errors()
                {
                    // Simulate an error message returned by validation
                    return collect([]);
                }
            });
        $mock = \Mockery::mock('overload:'.Repository::class);
        $mockRedis = \Mockery::mock('overload:'.RedisRepository::class);
        $data = [
            "id" => 1,
            "title" => "Filosofi Teras",
            "description" => "Kita memiliki kebiasaan membesar-besarkan kesedihan. Kita tercabik di antara hal-hal masa kini dan hal-hal yang baru terjadi. Pikirlah apakah sudah ada bukti yang pasti mengenai kesusahan masa depan. Karena sering kali kita lebih disusahkan kekhawatiran kita sendiri",
            "publish_date" => "2018-01-01 10:10:10",
            "author_id" => 1
        ];
        $mock->shouldReceive('condition')->andReturn((object)["id" => 1, "author_id" => 1]);
        $mock->shouldReceive('update')->andReturn(true);
        $mockRedis->shouldReceive('deleteRedisData')->andReturn(true);
        unset($data['id']);
        $call = (new AddUpdateBookService(new Request($data)))->setBookId(1)->call();
        // echo "\nresult : " . json_encode($call, JSON_PRETTY_PRINT);
        $this->assertEquals(200, $call->status());
    }

    /**
     * Test update book failed when book not found
     */
    public function test_update_book_failed_when_book_not_found()
    {
        // Mock the Validator facade
        Validator::shouldReceive('make')
            ->once()
            ->andReturn(new class extends ValidatorInstance {
                public function __construct() {}
                public function fails()
                {
                    return false; // Simulate that the validation success
                }
                public function validated()
                {
                    return [
                        "title" => "Filosofi Teras",
                        "description" => "Kita memiliki kebiasaan membesar-besarkan kesedihan. Kita tercabik di antara hal-hal masa kini dan hal-hal yang baru terjadi. Pikirlah apakah sudah ada bukti yang pasti mengenai kesusahan masa depan. Karena sering kali kita lebih disusahkan kekhawatiran kita sendiri",
                        "publish_date" => "2018-01-01",
                        "author_id" => 1
                    ];
                }
                public function errors()
                {
                    // Simulate an error message returned by validation
                    return collect([]);
                }
            });
        $mock = \Mockery::mock('overload:'.Repository::class);
        $mockRedis = \Mockery::mock('overload:'.RedisRepository::class);
        $data = [
            "id" => 1,
            "title" => "Filosofi Teras",
            "description" => "Kita memiliki kebiasaan membesar-besarkan kesedihan. Kita tercabik di antara hal-hal masa kini dan hal-hal yang baru terjadi. Pikirlah apakah sudah ada bukti yang pasti mengenai kesusahan masa depan. Karena sering kali kita lebih disusahkan kekhawatiran kita sendiri",
            "publish_date" => "2018-01-01 10:10:10",
            "author_id" => 1
        ];
        $mock->shouldReceive('condition')->andReturn(null);
        $mock->shouldReceive('update')->andReturn(true);
        $mockRedis->shouldReceive('deleteRedisData')->andReturn(true);
        unset($data['id']);
        $call = (new AddUpdateBookService(new Request($data)))->setBookId(1)->call();
        // echo "\nresult : " . json_encode($call, JSON_PRETTY_PRINT);
        $this->assertEquals("book not found", $call->message());
    }
}
