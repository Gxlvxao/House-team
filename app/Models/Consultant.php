<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'photo',
        'phone',
        'email',
        'bio',
        'order',
        'is_active',
        'facebook',
        'instagram',
        'linkedin',
        'tiktok',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!$this->photo) {
            return asset('img/default-avatar.png'); 
        }

        if (str_contains($this->photo, '/')) {
            return asset('storage/' . $this->photo);
        }

        return asset('img/team/' . $this->photo);
    }
}