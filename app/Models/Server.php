<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'ip', 'user', 'country_id',
    ];

    public function country()
    {
        return $this -> belongsTo(Country::class, 'country_id', 'id');
    }
}
