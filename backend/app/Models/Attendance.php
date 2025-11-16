<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'status',
        'note',
        'recorded_by'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
