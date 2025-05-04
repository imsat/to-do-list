<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'user_id',
        'status',
    ];

    public function scopeSearch($query)
    {
        $search = request()->get('search');
        return empty($search) ? $query : $query->where('title', 'LIKE', '%' . $search . '%');
    }
}
