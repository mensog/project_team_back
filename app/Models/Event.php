<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        'status',
        'description',
        'project_id',
        'preview_image',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
