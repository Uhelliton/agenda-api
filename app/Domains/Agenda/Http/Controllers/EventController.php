<?php

namespace App\Domains\Agenda\Http\Controllers;
use App\Domains\Agenda\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Http\Request;
use App\Core\Http\Controllers\Controller;

class EventController extends Controller
{
    protected EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function index()
    {
        $events = $this->eventRepository->getAll();
        return response()->json($events);
    }


    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
       $event = $this->eventRepository->create($request->all());
        return response()->json($request->all());
    }
}
