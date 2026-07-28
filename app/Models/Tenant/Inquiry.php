<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $connection = 'tenant';

    protected $fillable = ['name', 'email', 'subject', 'message'];
}
