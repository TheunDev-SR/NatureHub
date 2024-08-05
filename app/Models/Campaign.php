<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = [
        'name',
        'title',
        'content',
        'image',
        'is_starred',
        'user_id'
    ];

    protected $table = 'campaign';
    protected $with = ['user', 'likes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sluggable(): array
    {

        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
