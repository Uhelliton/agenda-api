<?php

namespace App\Domains\Agenda\Models;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    /**
     * table this model
     *
     * @var string
     */
    protected $table = 'events_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];


    /**
     * @var bool
     */
    public $timestamps = false;
}
