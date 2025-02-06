<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class program_pdf extends Model
{
    use HasFactory;
    protected $table = 'program_pdfs';
    protected $fillable = ['program_name', 'program_pdf', 'is_deleted'];

    public function program_dts()
    {
        return $this->belongsTo(InclusivePackages::class, 'program_id');
    }


}
