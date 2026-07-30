<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Tenant\User;

class Review extends Model
{
    use SoftDeletes;

    protected $connection = 'tenant';

    protected $fillable = [
        'reviewable_type',
        'reviewable_id',
        'user_id',
        'author_name',
        'author_email',
        'rating',
        'title',
        'comment',
        'status',
        'is_verified_buyer',
        'helpful_votes',
        'admin_reply',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified_buyer' => 'boolean',
        'helpful_votes' => 'integer',
    ];

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(ReviewMedia::class);
    }
}
