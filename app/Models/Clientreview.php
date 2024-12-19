<?php



namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clientreview extends Authenticatable
{
    use HasFactory;
    protected $table = 'client_review';
    protected $fillable = ['program_id', 'client_name', 'client_pic', 'client_review', 'review_dt', 'rating', 'status', 'is_deleted'];
    
    public function program_dts()
    {
        return $this->belongsTo(InclusivePackages::class, 'program_id');
    }
    
}
