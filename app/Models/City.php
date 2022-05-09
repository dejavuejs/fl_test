<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * City model class
 */
class City extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'forecast'
    ];
}
