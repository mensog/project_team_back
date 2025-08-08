<?php

namespace App\Models;

use App\Events\ProjectCompleted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'certificate',
        'status',
        'user_id',
        'preview_image',
        'start_date',
        'end_date',
        'is_approved',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'project_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }

    public function checkAndUpdateStatus()
    {
        if ($this->end_date && now()->isAfter($this->end_date) && $this->status !== 'completed') {
            $this->update(['status' => 'completed']);
            event(new ProjectCompleted($this));
        }
    }
}
