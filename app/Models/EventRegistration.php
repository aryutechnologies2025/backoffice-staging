<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    public function event()
    {
        return $this->belongsTo(ProgramEvents::class, 'event_id', 'id')->select('event_name','id');
    }
}
