<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Category extends Model
{
    //
    use SoftDeletes, \App\Models\Traits\Uuid;

    protected $fillable = ['name', 'description', 'is_active'];
    protected $dates = ['deleted_at', 'teste'];
    protected $casts = [
        'id' => 'string'
    ];

    // o id estava gerado como 0,1,2,3...agora sera com UUID
    public $incrementing = false;


}
