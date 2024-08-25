<?php

namespace Tests\Unit\Author;

use App\Repositories\Repository;
use App\Services\Author\GetAuthorService;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class GetAuthorTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test get author success
     */
    public function test_get_author_success()
    {
        $mock = \Mockery::mock('overload:'.Repository::class);
        $data = [
            [
                "id" => 1,
                "name" => "Henry Manampiring",
                "bio" => "Henry Manampiring atau akrab disapa Piring adalah seorang penulis dan juga blogger.",
                "birth_date" => "1970-01-01"
            ]
        ];
        // Creating a LengthAwarePaginator instance
        $paginator = new LengthAwarePaginator(
            $data, // Items to paginate
            1, // Total items (adjust this according to your actual dataset)
            20, // Items per page (adjust according to your requirement)
            1 // Current page
        );
        $mock->shouldReceive('paginate')->andReturn($paginator);
        $call = (new GetAuthorService())->call();
        // echo "\nresult : " . json_encode($call, JSON_PRETTY_PRINT);
        $this->assertEquals(200, $call->status());
    }

    /**
     * Test show author success
     */
    public function test_show_author_success()
    {
        $mock = \Mockery::mock('overload:'.Repository::class);
        $data =  (object)[
                "id" => 1,
                "name" => "Henry Manampiring",
                "bio" => "Henry Manampiring atau akrab disapa Piring adalah seorang penulis dan juga blogger.",
                "birth_date" => "1970-01-01"
            ];
        $mock->shouldReceive('show')->andReturn($data);
        $call = (new GetAuthorService())->setAuthorId(1)->call();
        // echo "\nresult : " . json_encode($call, JSON_PRETTY_PRINT);
        $this->assertEquals(200, $call->status());
    }

    /**
     * Test show author failed
     */
    public function test_show_author_failed()
    {
        $mock = \Mockery::mock('overload:'.Repository::class);
        $data = null;
        $mock->shouldReceive('show')->andReturn($data);
        $call = (new GetAuthorService())->setAuthorId(null)->call();
        // echo "\nresult : " . json_encode($call, JSON_PRETTY_PRINT);
        $this->assertEquals(400, $call->status());
    }
}
