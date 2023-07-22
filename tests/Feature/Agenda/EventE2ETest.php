<?php

namespace Tests\Feature\Agenda;

use App\Domains\Agenda\Http\Requests\EventStoreRequest;
use App\Domains\Agenda\Http\Requests\EventUpdateRequest;
use App\Domains\Agenda\Models\Event;
use App\Domains\User\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Request;
use Laravel\Sanctum\Sanctum;
use Tests\Mockers\EventMocker;
use Tests\TestCase;
use Mockery;
use App\Domains\Agenda\Http\Controllers\EventController;

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

    public function testIndex()
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
}
