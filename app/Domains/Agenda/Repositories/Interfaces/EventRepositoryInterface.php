<?php
namespace App\Domains\Agenda\Repositories\Interfaces;

use App\Domains\Agenda\Models\Event;

interface EventRepositoryInterface
{
    public function getAll();

    public function findById(int $id);

    public function findByStartDate(string $date);

    public function create(array $attributes);

    public function update(array $attributes, int $id);

    public function delete(int $id);
}
