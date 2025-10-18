<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'cancellation_policy',
        'privacy_rules',
        'staff_workflows',
    ];
}