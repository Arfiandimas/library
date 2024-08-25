<?php

namespace Tests\Unit\Author;

use App\Repositories\Repository;
use App\Services\Author\AddUpdateAuthorService;
use Illuminate\Http\Request;
use Tests\TestCase;

class AddUpdateAuthorTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test store author success
     */
    public function test_store_author_success()
    {
        $mock = \Mockery::mock('overload:'.Repository::class);
        $data = [
            "id" => 1,
            "name" => "Henry Manampiring",
            "bio" => "Henry Manampiring atau akrab disapa Piring adalah seorang penulis dan juga blogger.",
            "birth_date" => "1970-01-01"
        ];
        $mock->shouldReceive('store')->andReturn((object)$data);
        unset($data['id']);
        $call = (new AddUpdateAuthorService(new Request($data)))->call();
        // echo "\nresult : " . json_encode($call, JSON_PRETTY_PRINT);
        $this->assertEquals(200, $call->status());
    }

    /**
     * Test store author failed
     */
    public function test_store_author_failed()
    {
        $mock = \Mockery::mock('overload:'.Repository::class);
        $data = [
            "id" => 1,
            "name" => "Henry Manampiring",
            "bio" => "Henry Manampiring atau akrab disapa Piring adalah seorang penulis dan juga blogger.",
            "birth_date" => "1970-01-01"
        ];
        $mock->shouldReceive('store')->andReturn((object)$data);
        unset($data['id']);
        unset($data['name']);
        $call = (new AddUpdateAuthorService(new Request($data)))->call();
        // echo "\nresult : " . json_encode($call, JSON_PRETTY_PRINT);
        $this->assertEquals(400, $call->status());
    }

    /**
     * Test update author success
     */
    public function test_update_author_success()
    {
        $mock = \Mockery::mock('overload:'.Repository::class);
        $data = [
            "id" => 1,
            "name" => "Henry Manampiring",
            "bio" => "Henry Manampiring atau akrab disapa Piring adalah seorang penulis dan juga blogger.",
            "birth_date" => "1970-01-01"
        ];
        $mock->shouldReceive('condition')->andReturn((object)$data);
        $mock->shouldReceive('update')->andReturn(true);
        unset($data['id']);
        unset($data['name']);
        $call = (new AddUpdateAuthorService(new Request($data)))->setAuthorId(1)->call();
        // echo "\nresult : " . json_encode($call, JSON_PRETTY_PRINT);
        $this->assertEquals(200, $call->status());
    }

    /**
     * Test update author failed
     */
    public function test_update_author_failed()
    {
        $mock = \Mockery::mock('overload:'.Repository::class);
        $data = [
            "id" => 1,
            "name" => "Henry Manampiring",
            "bio" => "Henry Manampiring atau akrab disapa Piring adalah seorang penulis dan juga blogger.",
            "birth_date" => "1970-01-01"
        ];
        $mock->shouldReceive('condition')->andReturn((object)$data);
        $mock->shouldReceive('update')->andReturn(true);
        unset($data['id']);
        $data['birth_date'] = "1970-01-01 10:20:10";
        $call = (new AddUpdateAuthorService(new Request($data)))->setAuthorId(1)->call();
        // echo "\nresult : " . json_encode($call, JSON_PRETTY_PRINT);
        $this->assertEquals(400, $call->status());
    }

    /**
     * Test update author failed when autor not found
     */
    public function test_update_author_failed_when_autor_not_found()
    {
        $mock = \Mockery::mock('overload:'.Repository::class);
        $data = [
            "id" => 1,
            "name" => "Henry Manampiring",
            "bio" => "Henry Manampiring atau akrab disapa Piring adalah seorang penulis dan juga blogger.",
            "birth_date" => "1970-01-01"
        ];
        $mock->shouldReceive('condition')->andReturn(null);
        $mock->shouldReceive('update')->andReturn(true);
        unset($data['id']);
        $call = (new AddUpdateAuthorService(new Request($data)))->setAuthorId(1)->call();
        // echo "\nresult : " . json_encode($call, JSON_PRETTY_PRINT);
        $this->assertEquals("author not found", $call->message());
    }
}
