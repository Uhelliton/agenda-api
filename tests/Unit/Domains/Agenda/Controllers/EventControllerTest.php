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

    public function test_update_with_event_notfound(): void
    {
        $request = new EventUpdateRequest();
        $eventId = 1;

        $request->request->add([
            'title' => 'evento titulo',
            'description'=> 'evento descrição',
            'type_id' => 1
        ]);

        $controller = new EventController($this->eventRepository);

        $this->eventRepository->shouldReceive('findById')
            ->once()
            ->with($eventId)
            ->andReturn(null);

        $this->expectException(HttpResponseException::class);
        $controller->update($request, $eventId);
    }

    public function test_update_with_event_finalization(): void
    {
        $request = new EventUpdateRequest();
        $eventId = 1;

        $request->request->add([
            'title' => 'evento titulo',
            'description'=> 'evento descrição',
            'type_id' => 1
        ]);

        $controller = new EventController($this->eventRepository);

        $this->eventRepository->shouldReceive('findById')
            ->once()
            ->with($eventId)
            ->andReturn(EventMocker::getEventFake(Event::STATUS_FINALIZATION));

        $this->expectException(HttpResponseException::class);
        $controller->update($request, $eventId);
    }

    public function test_update_with_success(): void
    {
        $request = new EventUpdateRequest();
        $eventId = 1;

        $request->request->add([
            'title' => 'evento titulo',
            'description'=> 'evento descrição',
            'type_id' => 1
        ]);

        $controller = new EventController($this->eventRepository);

        $this->eventRepository->shouldReceive('findById')
            ->once()
            ->with($eventId)
            ->andReturn(EventMocker::getEventFake(Event::STATUS_OPEN));

        $this->eventRepository->shouldReceive('update')
            ->once()
            ->with($request->only([
                'title' => 'evento titulo',
                'description'=> 'evento descrição',
                'type_id' => 1
            ]), $eventId)
            ->andReturn(EventMocker::getEventFake(Event::STATUS_OPEN));

        $response = $controller->update($request, $eventId);
        $this->assertEquals(200, $response->status());
    }

    public function test_delete_with_event_notfound(): void
    {
        $eventId = 1;
        $controller = new EventController($this->eventRepository);

        $this->eventRepository->shouldReceive('findById')
            ->once()
            ->with($eventId)
            ->andReturn(null);

        $this->expectException(HttpResponseException::class);
        $controller->destroy($eventId);
    }

    public function test_delete_with_success(): void
    {
        $eventId = 1;
        $controller = new EventController($this->eventRepository);

        $this->eventRepository->shouldReceive('findById')
            ->once()
            ->with($eventId)
            ->andReturn(EventMocker::getEventFake());

        $this->eventRepository->shouldReceive('delete')
            ->once()
            ->with($eventId)
            ->andReturn(true);

        $response = $controller->destroy($eventId);

        $this->assertEquals(200, $response->status());
        $this->assertTrue((bool) $response->content());
    }

    public function test_done_event_with_status_finished_return_exception(): void
    {
        $eventId = 1;
        $controller = new EventController($this->eventRepository);

        $this->eventRepository->shouldReceive('findById')
            ->once()
            ->with($eventId)
            ->andReturn(EventMocker::getEventFake(Event::STATUS_FINALIZATION));

        $this->expectException(HttpResponseException::class);
        $controller->done($eventId);
    }

    public function test_done_event_with_success(): void
    {
        $eventId = 1;
        $attributesData = [
            'finalization_at' => now()->format('Y-m-d'),
            'status' => Event::STATUS_FINALIZATION,
        ];

        $controller = new EventController($this->eventRepository);

        $this->eventRepository->shouldReceive('findById')
            ->once()
            ->with($eventId)
            ->andReturn(EventMocker::getEventFake(Event::STATUS_OPEN));

        $this->eventRepository->shouldReceive('update')
        ->once()
        ->with($attributesData, $eventId)
        ->andReturn(EventMocker::getEventFake(Event::STATUS_FINALIZATION));

        $response = $controller->done($eventId);
        $responseContent = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->status());
        $this->assertEquals($eventId, $responseContent['id']);
        $this->assertEquals(Event::STATUS_FINALIZATION, $responseContent['status']);
    }
}
