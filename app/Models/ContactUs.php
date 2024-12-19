<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs  extends Authenticatable
{
    use HasFactory;
    protected $table = 'contact_us';
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'message',
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}
}
