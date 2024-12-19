<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Authenticatable
{
    use HasFactory;
    protected $table = 'property_details';

    // protected $fillable = [
    //     'property_title',
    //     'property_type',
    //     'prop_cat',
    //     'type',
    //     'state',
    //     'city',
    //     'address',
    //     'country',
    //     'start_date',
    //     'return_date',
    //     'total_days',
    //     'total_room',
    //     'member_capacity',
    //     'price',
    //     'coupon_code',
    //     'camp_rule',
    //     'important_info',
    //     'is_deleted',
    //     'created_date',
    //     'created_by',
    //     'status',
    // ];
}
