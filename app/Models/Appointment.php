<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    protected $fillable = ['user_id','title','description','start_time','end_time','location','status'];

     protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function user () {
        return $this->belongsTo(User::class);
    }
}
