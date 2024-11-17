<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyTree extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'tree_data',
    ];

    protected $casts = [
        'tree_data' => 'array',
    ];
}

