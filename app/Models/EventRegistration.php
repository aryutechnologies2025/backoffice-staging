<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $table = 'event_registrations';
    public $timestamps = true;

    protected $fillable = [
        'event_id',
        'name',
        'first_name',
        'last_name',
        'profile_image',
        'email',
        'phone',
        'dob',
        'street',
        'city',
        'state',
        'zip_province_code',
        'country',
        'preferred_lang',
        'newsletter_sub',
        'terms_condition',
        'created_by',
        'created_date',
        'anniversary_date',
        'notes',
        'status',
    ];


    public function event()
    {
        return $this->belongsTo(ProgramEvents::class, 'event_id', 'id')->select('event_name','id');
    }
}
