<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $connection = 'tenant';

    protected $fillable = ['email'];
}
