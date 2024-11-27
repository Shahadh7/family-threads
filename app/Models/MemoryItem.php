<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'added_by_user_id',
        'description',
        'family_id',
        'file_id'
    ];

    public function thread()
    {
        return $this->belongsTo(MemoryThread::class, 'thread_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'added_by_user_id');
    }

    public function timeCapsule()
    {
        return $this->hasOne(TimeCapsule::class, 'item_id');
    }

    public function keepsake()
    {
        return $this->hasOne(Keepsake::class, 'item_id');
    }
}

