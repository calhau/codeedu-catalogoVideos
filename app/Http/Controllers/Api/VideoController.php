<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BasicCrudController;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends BasicCrudController
{
    private $rules;
    //  =
    // [
    //     'name' => 'required|max:255',
    //     'is_active' => 'boolean'
    // ]
    // ;
    public function __construct()
    {
        $this->rules = [
            'title' => 'required|max:255',
            'description' => 'required',
            'year_lauched' => 'required|date_format:Y',
            'opened' => 'boolean',
            'rating' => 'required|in:' . implode(',', Video::RATING_LIST),
            'duration' => 'required|integer'
        ];
        // dump($this->rules);
    }

    protected function model(){
        return Video::class;
    }

    protected function rulesStore(){
        return $this->rules;
    }
    protected function rulesUpdate(){
        return $this->rules;
    }
}
