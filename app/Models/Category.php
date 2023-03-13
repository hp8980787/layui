<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected $attributes = [
        'route'=>'test'
    ];

    protected function route(): Attribute
    {
        return new Attribute(set: fn($value) => $value ?? 'sdasa');
    }

}
