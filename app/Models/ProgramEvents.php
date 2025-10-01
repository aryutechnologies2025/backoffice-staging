<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramEvents extends Model
{
    use HasFactory;

    protected $fillable = [
        'cover_img', 'upload_image_name', 'alternate_image_name', 'title',
        'start_datetime', 'end_datetime', 'send_link', 'embed_map',
        'welcome_msg', 'hosted_by', 'location_type', 'event_description'
    ];

    public function registration()
    {
        return $this->hasMany(EventRegistration::class, 'event_id');
    }
}