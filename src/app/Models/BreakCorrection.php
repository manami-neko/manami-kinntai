<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakCorrection extends Model
{
    use HasFactory;

    protected $fillable = [
        'correction_id',
        'start',
        'end',
    ];

    public function correction()
    {
        return $this->belongsTo(Correction::class);
    }
}
