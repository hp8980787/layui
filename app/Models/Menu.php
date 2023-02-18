<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tarit\ModelFilterTrait;

class Menu extends Model
{
    use HasFactory, SoftDeletes, ModelFilterTrait;

    protected $fillable = [
        'title', 'icon', 'type', 'open_type', 'href', 'status', 'parent_id'
    ];

    public function icon(): Attribute
    {
        return new Attribute(
//            get: fn($value) => 'layui-icon ' . $value,
//            set: fn($value) =>'layui-icon '. $value,
        );
    }

    public function href(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value?'/admin' . $value:'',
    );
    }

}
