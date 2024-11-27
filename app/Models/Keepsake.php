<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keepsake extends Model
{
    use HasFactory;

    protected $fillable = [
        'given_to_user_id',
        'memory_item_id'
    ];

    public function memoryItem()
    {
        return $this->belongsTo(MemoryItem::class, 'item_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'assignable_id');
    }
}

