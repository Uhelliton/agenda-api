<?php

namespace App\Domains\Agenda\Http\Controllers;

use App\Domains\Agenda\Http\Requests\EventStoreRequest;
use App\Domains\Agenda\Http\Requests\EventUpdateRequest;
use App\Domains\Agenda\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

use App\Core\Http\Controllers\Controller;
use App\Domains\Agenda\Models\Event;

class EventController extends Controller
{
    protected EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $events = $this->eventRepository->getAll();

        return response()->json($events);
    }


    /**
     * @param  EventStoreRequest  $request
     * @return JsonResponse
     */
    public function store(EventStoreRequest $request): JsonResponse
    {
        $event = $this->eventRepository->findByStartDate($request->get('start_date'));

        if ($event) {
            throw new HttpResponseException(response()->json([
                'message' => 'O Evento já existe para data de início e o usuário fornecido',
            ], 403));
        }

        $eventCreate = $this->eventRepository->create($request->all());
        return response()->json($eventCreate, 201);
    }

    /**
     * @param EventUpdateRequest $request
     * @param int $id
     * @return mixed
     */
    public function update(EventUpdateRequest $request, int $id): JsonResponse
    {
        $event = $this->eventRepository->findById($id);

        if (!$event)
            throw new HttpResponseException(response()->json(['message' => 'Evento não encontrado'], 404));

        if ($event->status === Event::STATUS_FINALIZATION) {
            throw new HttpResponseException(response()->json([
                'message' => 'Evento não pode ser atualizado, pois o mesmo já foi finalizado',
            ], 403));
        }

        $attributes = $request->only(['title', 'description', 'type_id']);
        $eventUpdate = $this->eventRepository->update($attributes, $id);

        return response()->json($eventUpdate);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $event = $this->eventRepository->findById($id);

        if (!$event) {
            throw new HttpResponseException(response()->json(['message' => 'Evento não encontrado'], 404));
        }

        $eventDelete = $this->eventRepository->delete($id);
        return response()->json($eventDelete);
    }
}
