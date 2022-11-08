<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'note',
        'due_date',
        'is_read'
    ];

    public function user()
    {
        return $this->hasOne(User::class,"id","user_id");
    }
}
