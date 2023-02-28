<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'files';
    protected $fillable = [
        'name', 'original_name', 'extension', 'path', 'model_id', 'model_type'
    ];

    public function model()
    {
        return $this -> morphTo();
    }

}
