<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['issue_details', 'call_id'];

    public function call()
    {
        return $this->belongsTo(Call::class);
    }
}
