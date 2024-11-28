<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemoryThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
        'date',
        'memory_item_id'
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function memoryItems()
    {
        return $this->hasMany(MemoryItem::class, 'thread_id');
    }
}

