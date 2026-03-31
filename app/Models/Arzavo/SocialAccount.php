<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Model;
use App\Models\Arzavo\User;

class SocialAccount extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
