<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $table = 'audit';

    protected $fillable = [
        'note',
        'thesis_defense_report_id',
        'student_id',
    ];
}

