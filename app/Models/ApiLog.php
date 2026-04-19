<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'endpoint',
        'method',
        'body',
        'headers',
        'ip',
        'user_id'
    ];

    protected $casts = [
        'body' => 'array',
        'headers' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}