<?php

namespace App\Domains\Agenda\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Domains\Agenda\Models
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $status
 * @property int $type_id
 * @property string|null $finalization_at
 */
class Event extends Model
{
    /**
     * table this model
     *
     * @var string
     */
    protected $table = 'events';

    public CONST STATUS_OPEN = 'Aberto';

    public CONST STATUS_FINALIZATION = 'Concluido';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'due_date',
        'status',
        'type_id',
        'user_id',
        'finalization_at'
    ];


    /**
     * @var bool
     */
    public $timestamps = true;
}
