<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;
use \App\Models\Traits\Uuid;

class CategoryTest extends TestCase
{
     public function testFillableAttribute()
    {

        // $this->assertTrue(true);
        $fillable = ['name', 'description', 'is_active'];
        $category = new Category();
        //Vou comparar a variavel criada com os campos existem em $fillable da classe Category
        $this->assertEquals( $fillable, $category->getFillable()
        );
    }

    public function testIfUseTraits()
    {
        //Classes que Category deve ter
        $traits = [
            SoftDeletes::class, Uuid::class
        ];

        //Aqui eu pego as classes q ela usa, porem so as keys para comparar sem chaves
        $categoryTraits = array_keys(class_uses(Category::class));

        $this->assertEquals($traits, $categoryTraits);
    }
    public function testCasts(){
        $casts = ['id' => 'string'];
        $category = new Category();
        $this->assertEquals($casts, $category->getCasts());
    }

    public function testIncrementing(){
        $category = new Category();
        $this->assertFalse($category->incrementing);
    }

    public function testDatesAttribute(){
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        $category = new Category();
        //  dd($category->getDates(), $dates);

        //Deu erro por conta dos indices dos arrays
        // $this->assertEquals($dates, $category->getDates());

        foreach($dates as $date){
            $this->assertContains($date, $category->getDates());
        }

        $this->assertCount(count($dates), $category->getDates());
    }
}
