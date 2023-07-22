<?php

namespace Tests\Feature\Agenda;

use App\Domains\Agenda\Http\Requests\EventStoreRequest;
use App\Domains\Agenda\Http\Requests\EventUpdateRequest;
use App\Domains\User\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\Mockers\EventMocker;
use Tests\TestCase;
use Mockery;

class EventE2ETest extends TestCase
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

    /** @test ***/
    public function it_should_all_events()
    {
        // simulate auth user
        Sanctum::actingAs(User::factory()->create());

        $this->eventRepository
            ->shouldReceive('paginate')
            ->once()
            ->andReturn(EventMocker::eventsPaginate());

        $response = $this->getJson('api/agenda/events');
        $response->assertStatus(200);
        $response->assertOk();
        $response->assertJson(EventMocker::eventsPaginate());
    }

    /** @test ***/
    public function it_should_create_event()
    {
        // simulate auth user
        Sanctum::actingAs(User::factory()->create());

        $request = new EventStoreRequest();

        $attributesData = [
            'start_date' => '2023-07-24',
            'title' => 'evento titulo',
            'description'=> 'evento descrição',
            'due_date' => '2023-07-26',
            'type_id'   => 1,
        ];

        $request->request->add($attributesData);

        $this->eventRepository
            ->shouldReceive('findByStartDate')
            ->once()
            ->with('2023-07-24')
            ->andReturn();

        $this->eventRepository
            ->shouldReceive('create')
            ->once()
            ->with($attributesData)
            ->andReturn($attributesData);

        $response = $this->postJson('api/agenda/events', $attributesData);
        $response->assertStatus(201);
    }



    /** @test ***/
    public function it_should_update_event()
    {
        // simulate auth user
        Sanctum::actingAs(User::factory()->create());

        $request = new EventUpdateRequest();

        $eventId = 1;
        $attributesData = [
            'title' => 'evento titulo',
            'description'=> 'evento descrição',
            'type_id'   => 1,
        ];

        $request->request->add($attributesData);

        $this->eventRepository
            ->shouldReceive('findById')
            ->once()
            ->with($eventId)
            ->andReturn(EventMocker::getEventFake());

        $this->eventRepository
            ->shouldReceive('update')
            ->once()
            ->with($attributesData, $eventId)
            ->andReturn($attributesData);

        $response = $this->putJson("api/agenda/events/{$eventId}", $attributesData);
        $response->assertStatus(200);
    }

    /** @test ***/
    public function it_should_return_404_on_update_event_if_no_exist()
    {
        // simulate auth user
        Sanctum::actingAs(User::factory()->create());

        $request = new EventStoreRequest();

        $eventId = 1;
        $attributesData = [
            'title' => 'evento titulo',
            'description'=> 'evento descrição',
            'type_id'   => 1,
        ];

        $request->request->add($attributesData);

        $this->eventRepository
            ->shouldReceive('findById')
            ->once()
            ->with($eventId)
            ->andReturn();

        $response = $this->putJson("api/agenda/events/{$eventId}", $attributesData);
        $response->assertStatus(404);
    }

    /** @test ***/
    public function it_should_return_404_on_delete_event_if_no_exist()
    {
        // simulate auth user
        Sanctum::actingAs(User::factory()->create());

        $eventId = 1;

        $this->eventRepository
            ->shouldReceive('findById')
            ->once()
            ->with($eventId)
            ->andReturn();

        $response = $this->deleteJson("api/agenda/events/{$eventId}");
        $response->assertStatus(404);
    }

    /** @test ***/
    public function it_should_delete_event()
    {
        // simulate auth user
        Sanctum::actingAs(User::factory()->create());

        $eventId = 1;

        $this->eventRepository
            ->shouldReceive('findById')
            ->once()
            ->with($eventId)
            ->andReturn(EventMocker::getEventFake());


        $this->eventRepository
            ->shouldReceive('delete')
            ->once()
            ->with($eventId)
            ->andReturn(true);

        $response = $this->deleteJson("api/agenda/events/{$eventId}");
        $response->assertStatus(200);
        $response->assertOk();

    }
}
