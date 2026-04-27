<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'activity_date',
    ];

    protected $casts = [
        'activity_date' => 'date',
    ];

    public function updates()
    {
        return $this->hasMany(ActivityUpdate::class);
    }

    public function latestUpdate()
    {
        return $this->hasOne(ActivityUpdate::class)->latestOfMany();
    }
}
