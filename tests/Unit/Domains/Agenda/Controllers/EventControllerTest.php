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


    public function test_store_with_valid_request(): void
    {
        $request = new EventStoreRequest();
        $request->request->add([
            'start_date' => '2023-07-22',
            'title' => 'evento titulo',
            'description'=> 'evento descrição',
            'due_date' => '2023-07-26',
            'type_id' => 1
        ]);

        $controller = new EventController($this->eventRepository);

        $this->eventRepository->shouldReceive('findByStartDate')
            ->once()
            ->with('2023-07-22')
            ->andReturn(null);

        $this->eventRepository->shouldReceive('create')
            ->once()
            ->andReturn(EventMocker::getEventFake());

        $response = $controller->store($request);
        $this->assertEquals(201, $response->status());
    }

    public function test_store_with_start_date_exist_expect_exception(): void
    {
        $request = new EventStoreRequest();
        $request->request->add([
            'start_date' => '2023-07-22'
        ]);

        $controller = new EventController($this->eventRepository);

        $this->eventRepository->shouldReceive('findByStartDate')
            ->once()
            ->with('2023-07-22')
            ->andReturn(EventMocker::getEventFake());

        $this->expectException(HttpResponseException::class);
        $controller->store($request);
    }


}
