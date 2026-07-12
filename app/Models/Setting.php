<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'setting';

    protected $guarded = [];

    public function getJamPelajaranAttribute($value)
    {
        if (!$value) {
            return [
                1 => ['mulai' => '07:00', 'selesai' => '07:45'],
                2 => ['mulai' => '07:45', 'selesai' => '08:30'],
                3 => ['mulai' => '08:30', 'selesai' => '09:15'],
                4 => ['mulai' => '09:15', 'selesai' => '10:00'],
                5 => ['mulai' => '10:00', 'selesai' => '10:45'],
                6 => ['mulai' => '10:45', 'selesai' => '11:30'],
                7 => ['mulai' => '11:30', 'selesai' => '12:15'],
                8 => ['mulai' => '12:15', 'selesai' => '13:00'],
                9 => ['mulai' => '13:00', 'selesai' => '13:45'],
                10 => ['mulai' => '13:45', 'selesai' => '14:30'],
            ];
        }
        return json_decode($value, true);
    }
}
