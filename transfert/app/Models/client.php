<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class client extends Model
{
    use HasFactory;
    protected $guarded = [ 

    ];
      public function scopeClientByTel(Builder $builder,string $tel){
        return $builder->where("numero",$tel);
    }
}
