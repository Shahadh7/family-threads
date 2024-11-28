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
        'title',
        'family_id',
        'file_id',
        'public',
        'can_be_viewed_by'
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function memoryThread()
    {
        return $this->hasOne(MemoryThread::class);
    }

    public function keepSake()
    {
        return $this->hasOne(Keepsake::class);
    }

    public function timeCapsule()
    {
        return $this->hasOne(TimeCapsule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'added_by_user_id');
    }
}

