<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThesisDefenseReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'supervisor_id',
        'title',
        'abstract',
        'defense_date',
        'defense_time',
        'room',
        'final_grade',
        'status',
        'comments',
    ];

    protected $casts = [
        'defense_date' => 'date',
        'defense_time' => 'datetime',
        'final_grade' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Professor::class, 'supervisor_id');
    }

    public function juryMembers(): HasMany
    {
        return $this->hasMany(JuryMember::class);
    }
}
