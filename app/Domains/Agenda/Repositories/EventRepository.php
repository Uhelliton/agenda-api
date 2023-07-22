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
    public function paginate()
    {
        return $this->model
            ->where(function ($query) {
                $queryInitialDate = request()->query('initialDate', '');
                $queryFinalDate = request()->query('finalDate', now()->format('Y-m-d'));

                if (!empty($queryInitialDate) && isValidDate($queryInitialDate)) {
                    $query->whereBetween('start_date', [$queryInitialDate, $queryFinalDate]);
                }
            })
            ->orderBy('id', 'desc')
            ->paginate();
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
     * @param string $date
     * @return mixed
     */
    public function findByStartDate(string $date)
    {
        return $this->model->where([
            'start_date' => $date,
            'user_id' => $this->authUser->id
        ])->first();
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
        $model->save();

        return $this->findById($id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->model->find($id)->delete();
    }
}
