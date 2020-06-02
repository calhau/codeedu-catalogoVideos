<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;
use \App\Models\Traits\Uuid;

//Comando para rodar os TESTES
// Classe especifica - vendor/bin/phpunit tests/Unit/CategoryTest.php
// Metodo especifico de um arquivo - vendor/bin/phpunit --filter testIfUseTraits tests/Unit/CategoryTest.php
// Metodo especifico de uma classe - vendor/bin/phpunit --filter CategoryTest::testIfUseTraits
class CategoryTest extends TestCase
{

    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = new Category();
    }

    public function testIfUseTraits()
    {
        //Esse metodo ira testar as CLASSES que devem exister
        $traits = [
            SoftDeletes::class,
            Uuid::class
        ];

        //Aqui eu pego as classes q ela usa, porem so as keys para comparar sem chaves
        $categoryTraits = array_keys(class_uses(Category::class));
        $this->assertEquals($traits, $categoryTraits);
    }

    public function testFillableAttribute()
    {
        //Vou comparar a variavel criada com os campos existem em $fillable da classe Category
        // $this->assertTrue(true);
        $fillable = ['name', 'description', 'is_active'];
        $this->assertEquals($fillable, $this->category->getFillable());
    }

    //Esse metodo abaixo poderia ser utiliaszdo o assertCanonico
    public function testDatesAttribute()
    {
        //Esse metodo Verifica a quantidade de campos de data existentes

        $dates = ['deleted_at', 'created_at', 'updated_at'];
        $category = new Category();
        // dd($category->getDates(), $dates);

        //Deu erro por conta dos indices dos arrays
        // $this->assertEquals($dates, $category->getDates());

        foreach ($dates as $date) {
            $this->assertContains($date, $category->getDates());
        }

        $this->assertCount(count($dates), $category->getDates());
    }

    public function testCasts()
    {
        $casts = ['id' => 'string', 'is_active' => 'boolean'];
        $category = new Category();
        $this->assertEquals($casts, $category->getCasts());
    }

    public function testIncrementing()
    {
        $category = new Category();
        $this->assertFalse($category->incrementing);
    }


}
