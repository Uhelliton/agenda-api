<?php

namespace App\Domains\Agenda\Http\Controllers;

use App\Domains\Agenda\Http\Requests\EventStoreRequest;
use App\Domains\Agenda\Http\Requests\EventUpdateRequest;
use App\Domains\Agenda\Repositories\Interfaces\EventRepositoryInterface as EventRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

use App\Core\Http\Controllers\Controller;
use App\Domains\Agenda\Models\Event;

class EventController extends Controller
{
    protected EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @OA\Get(
     *     tags={"Agenda"},
     *     description="listagem de eventos",
     *     path="/api/agenda/events",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *          name="initialDate",
     *          in="query",
     *          required=false,
     *         description="ex: 2023-01-01",
     *     ),
     *     @OA\Parameter(
     *          name="finalDate",
     *          in="query",
     *          required=false,
     *          description="ex: 2023-01-15",
     *     ),
     *    @OA\SecurityScheme(
     *      securityScheme="bearerAuth",
     *      in="header",
     *      name="bearerAuth",
     *      type="http",
     *      scheme="bearer",
     *      bearerFormat="JWT",
     * ),
     *     @OA\Response(
     *          response=200,
     *          description="",
     *          @OA\JsonContent(
     *              @OA\Property(property="current_page", type="number", example="5"),
     *              @OA\Property(property="total", type="number", example="5"),
     *              @OA\Property(property="per_page", type="number", example="15"),
     *              @OA\Property(
     *                property="data",
     *                type="array",
     *                   @OA\Items(
    *                    @OA\Property(property="id", type="integer", example="1"),
     *                   @OA\Property(property="title", type="string", example="Evento X"),
     *                   @OA\Property(property="description", type="string", example="descrição"),
     *                   @OA\Property(property="start_date", type="string", example="2023-07-22"),
     *                   @OA\Property(property="due_date", type="string", example="2023-07-26"),
     *                   @OA\Property(property="type_id", type="integer", example="1"),
     *                ),
     *             ),
     *          )
     *     ),
     * ),
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $events = $this->eventRepository->paginate();

        return response()->json($events);
    }


    /**
     * @OA\Post(
     *  tags={"Agenda"},
     *  description="registra um novo evento",
     *  path="/api/agenda/events",
     *  security={{"sanctum":{}}},
     *  @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *             required={"title","description","start_date","due_date", "type_id"},
     *             @OA\Property(property="title", type="string", example="Evento X"),
     *             @OA\Property(property="description", type="string", example="descrição"),
     *             @OA\Property(property="start_date", type="string", example="2023-07-22"),
     *             @OA\Property(property="due_date", type="string", example="2023-07-26"),
     *             @OA\Property(property="type_id", type="integer", example="1"),
     *          )
     *      ),
     *  ),
     *  @OA\Response(
     *    response=201,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="integer", example="1"),
     *       @OA\Property(property="title", type="string", example="Evento X"),
     *       @OA\Property(property="description", type="string", example="descrição"),
     *       @OA\Property(property="start_date", type="string", example="2023-07-22"),
     *       @OA\Property(property="due_date", type="string", example="2023-07-26"),
     *      @OA\Property(property="type_id", type="integer", example="1"),
     *    )
     *  ),
     * )
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
     * @OA\Put(
     *  tags={"Agenda"},
     *  description="atualiza um evento",
     *  path="/api/agenda/events/{id}",
     *  security={{"sanctum":{}}},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,
     *    description="",
     *    @OA\Schema(
     *       type="string"
     *    ),
     * ),
    *   @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *             required={"title","description", "type_id"},
     *             @OA\Property(property="title", type="string", example="Evento X"),
     *             @OA\Property(property="description", type="string", example="descrição"),
     *             @OA\Property(property="type_id", type="integer", example="1"),
     *          )
     *      ),
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Evento não encontrado"),
     *    )
     *  ),
     *  @OA\Response(
     *    response=403,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Evento não pode ser atualizado, pois o mesmo já foi finalizado"),
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="integer", example="1"),
     *       @OA\Property(property="title", type="string", example="Evento X"),
     *       @OA\Property(property="description", type="string", example="descrição"),
     *       @OA\Property(property="start_date", type="string", example="2023-07-22"),
     *       @OA\Property(property="due_date", type="string", example="2023-07-26"),
     *      @OA\Property(property="type_id", type="integer", example="1"),
     *    )
     *  ),
     * )
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
     * @OA\Patch(
     *  tags={"Agenda"},
     *  description="Finaliza um evento",
     *  path="/api/agenda/events/{id}/done",
     *  security={{"sanctum":{}}},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,
     *    description="",
     *    @OA\Schema(
     *       type="string"
     *    ),
     * ),
     *  @OA\Response(
     *    response=403,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Este evento já foi finalizado!"),
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="integer", example="1"),
     *       @OA\Property(property="title", type="string", example="Evento X"),
     *       @OA\Property(property="description", type="string", example="descrição"),
     *       @OA\Property(property="start_date", type="string", example="2023-07-22"),
     *       @OA\Property(property="due_date", type="string", example="2023-07-26"),
     *      @OA\Property(property="type_id", type="integer", example="1"),
     *    )
     *  ),
     * )
     * @param int $id
     * @return JsonResponse
     */
    public function done(int $id): JsonResponse
    {
        $event = $this->eventRepository->findById($id);

        if ($event->status === Event::STATUS_FINALIZATION) {
            throw new HttpResponseException(response()->json([
                'message' => 'Este evento já foi finalizado!',
            ], 403));
        }

        $attributes = [
            'finalization_at' => now()->format('Y-m-d'),
            'status' => Event::STATUS_FINALIZATION,
        ];
        $eventUpdate = $this->eventRepository->update($attributes, $id);

        return response()->json($eventUpdate);
    }

    /**
     * @OA\Delete(
     *  tags={"Agenda"},
     *  description="remove um evento",
     *  path="/api/agenda/events/{id}",
     *  security={{"sanctum":{}}},
     * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,
     *    description="",
     *    @OA\Schema(
     *       type="string"
     *    ),
     * ),
     *  @OA\Response(
     *    response=200,
     *    description="",
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Evento não encontrado"),
     *    )
     *  ),
     * )
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
