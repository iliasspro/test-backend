<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $fillable = ['call_time', 'duration', 'subject'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}