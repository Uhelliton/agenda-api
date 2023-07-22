<?php

namespace Tests\Unit\Domains\Agenda\Controllers;

use App\Domains\Agenda\Http\Requests\EventStoreRequest;
use App\Domains\Agenda\Http\Requests\EventUpdateRequest;
use App\Domains\Agenda\Models\Event;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Request;
use Tests\Mockers\EventMocker;
use Tests\TestCase;
use Mockery;
use App\Domains\Agenda\Http\Controllers\EventController;

class EventControllerTest extends TestCase
{
    private $eventRepository;

    public function setUp(): void
    {
        parent::setup();

        // create a mock of the post repository interface and inject it into the
        // IoC container
        $this->eventRepository = Mockery::mock('App\Domains\Agenda\Repositories\Interfaces\EventRepositoryInterface');
        $this->app->instance('App\Domains\Agenda\Repositories\Interfaces\EventRepositoryInterface', $this->eventRepository);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_return_empty(): void
    {
        $request = new Request();
        $controller = new EventController($this->eventRepository);

        $this->eventRepository->shouldReceive('paginate')
             ->once()
             ->andReturn(array());

        $response = $controller->index();
        $this->assertEquals(200, $response->status());
    }

    public function test_index_return_with_data(): void
    {
        $events = EventMocker::eventsPaginate();

        $controller = new EventController($this->eventRepository);

        $this->eventRepository->shouldReceive('paginate')
            ->once()
            ->andReturn($events);

        $response = $controller->index();
        $eventData = json_decode($response->content(), true);

        $this->assertCount(2, $eventData['data']);
        $this->assertIsArray($eventData['data']);
    }



}
