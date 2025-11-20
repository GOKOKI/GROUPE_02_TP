<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit;

class AttribuerNoteController extends Controller
{
    public function attribuerNote(Request $request)
    {
        // Validation simple
        $data = $request->validate([
            'note' => 'required|numeric',
            'thesis_defense_report_id' => 'required|integer',
            'student_id' => 'required|integer',
        ]);

        // Enregistrement audit
        Audit::create([
            'note' => $data['note'],
            'thesis_defense_report_id' => $data['thesis_defense_report_id'],
            'student_id' => $data['student_id'],
        ]);

        return response()->json(['message' => 'Note attribuée'], 201);
    }
}

