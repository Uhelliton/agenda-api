<?php

namespace Tests\Unit\Agenda\Controllers;

use App\Domains\Agenda\Models\Event;
use Illuminate\Support\Facades\Request;
use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Mockery\MockInterface;

use App\Domains\Agenda\Http\Controllers\EventController;
use App\Domains\Agenda\Repositories\Interfaces\EventRepositoryInterface;

class EventControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $mock;

    public function setUp(): void
    {
        $this->mock = Mockery::mock(EventRepositoryInterface::class);
        parent::setUp();
    }

    /**
     * A basic test example.
     */
    public function test_index_returns(): void
    {
        // $events =  Event::factory()->create();

        $request = new Request();

        $controller = new EventController($this->mock);

        $this->mock->shouldReceive('getAll')
             ->once()
             ->with(3)
             ->andReturn([]);

        $controller->index($request);
        $this->assertTrue(true);
    }
}
