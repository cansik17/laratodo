<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteCategory extends Model
{
    use HasFactory;

    //protected $table = "note_categories";

    protected $fillable = [
        'title',
        'status',
    ];
}
