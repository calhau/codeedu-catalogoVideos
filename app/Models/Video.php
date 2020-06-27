<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;

class Video extends Model
{
    use SoftDeletes, Uuid;

    const RATING_LIST = ['L', '10', '12', '14', '16' , '18'];

    protected $fillable = [
        'title',
        'description',
        'year_lauched',
        'opened',
        'rating',
        'duration'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'id' => 'string',
        'opened' => 'boolean',
        'year_lauched' => 'integer',
        'duration' => 'integer',
    ];

    public $incrementing = false;

    public function categories(){
        //caso o nome da tabela não esteja no padrao do laravel, passar como segundo parametro na função abaixo
        return $this->belongsToMany(Category::class);
    }
    public function genres(){
        //caso o nome da tabela não esteja no padrao do laravel, passar como segundo parametro na função abaixo
        return $this->belongsToMany(Genre::class);
    }
}
