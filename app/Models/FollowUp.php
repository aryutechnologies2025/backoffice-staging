<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'enquiry_id',
        'follow_up_date',
        'interest_prospect',
        'lead_source',
        'lead_status',
        'follow_up_notes',
        'action_required',
        'deal_value',
        'assigned_to',
        'next_follow_up_date',
        'home_id',
    ];

    // Define relationship with EnquiryDetail (one-to-many)
    public function enquiry()
    {
        return $this->belongsTo(EnquiryDetail::class);
    }
}
