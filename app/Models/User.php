<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    protected $fillable = [
        'first_name',
        'last_name',
        'profile_image',
        'email',
        'password',
        'dob',
        'phone',
        'street',
        'city',
        'state',
        'zip_province_code',
        'country',
        'preferred_lang',
        'newsletter_sub',
        'terms_condition',
        'status',
        'is_deleted',
        'created_by',
        'created_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->hasMany(Review::class, 'user_id'); // 'user_id' is the foreign key in the reviews table
    }
    public function enquiries()
{
    return $this->hasMany(EnquiryDetail::class);
}

}
