<?php

namespace App\Domains\Agenda\Http\Controllers;
use App\Domains\Agenda\Http\Requests\EventStoreRequest;
use App\Domains\Agenda\Repositories\Interfaces\EventRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use App\Core\Http\Controllers\Controller;

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
                'message'   => 'O Evento já existe para data de início e o usuário fornecido',
            ], 403));
        }

        $eventCreate = $this->eventRepository->create($request->all());
        return response()->json($eventCreate);
    }
}
