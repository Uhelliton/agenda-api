<?php
namespace App\Domains\Agenda\Repositories\Interfaces;

interface EventRepositoryInterface
{
    public function getAll();

    public function findById(int $id);

    public function create(array $attributes);

    public function update(array $attributes, int $id);

    public function delete(int $id);
}