<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JuryMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_defense_report_id',
        'professor_id',
        'role',
        'grade',
        'comments',
    ];

    protected $casts = [
        'grade' => 'decimal:2',
    ];

    public function thesisDefenseReport(): BelongsTo
    {
        return $this->belongsTo(ThesisDefenseReport::class);
    }

    public function professor(): BelongsTo
    {
        return $this->belongsTo(Professor::class);
    }
}
