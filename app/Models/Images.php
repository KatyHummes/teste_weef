<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    use HasFactory;

    protected $fillable = [
        'events_id',
        'photo_path',
    ];

    public function events()
    {
        return $this->belongsTo(Events::class);
    }
}
