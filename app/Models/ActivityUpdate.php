<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'user_id',
        'status',
        'remark',
        'updated_at_time',
    ];

    protected $casts = [
        'updated_at_time' => 'datetime',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
