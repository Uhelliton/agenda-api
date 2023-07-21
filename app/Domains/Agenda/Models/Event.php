<?php

namespace App\Domains\Agenda\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * table this model
     *
     * @var string
     */
    protected $table = 'events';

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
