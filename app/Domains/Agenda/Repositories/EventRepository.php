<?php
namespace App\Domains\Agenda\Repositories;

use App\Domains\Agenda\Models\Event;
use App\Domains\Agenda\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;

class EventRepository implements EventRepositoryInterface
{
    private Event $model;

    private ?Authenticatable $authUser;

    /**
     * @param Event $event
     */
    public function __construct(Event $event)
    {
        $this->model = $event;
        $this->authUser = auth('sanctum')->user();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->all();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes): Event
    {
        return $this->model->create(
            array_merge(
                $attributes,
                ['user_id' => $this->authUser->id]
            )
        );
    }

    /**
     * @param array $attributes
     * @param int $id
     * @return mixed
     */
    public function update(array $attributes, int $id)
    {
        $model = $this->model->find($id);
        $model->fill($attributes);

        return $model->save();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        $model = $this->model->find($id);

        return $model->delete();
    }
}
