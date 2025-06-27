<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'status',
        'date',
        'type',
        'preview_image',
    ];
}
