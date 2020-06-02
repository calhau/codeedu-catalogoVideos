<?php

namespace Tests\Unit;

use App\Models\Genre;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;
use \App\Models\Traits\Uuid;

//Comando para rodar os TESTES
// Classe especifica - vendor/bin/phpunit tests/Unit/CategoryTest.php
// Metodo especifico de um arquivo - vendor/bin/phpunit --filter testIfUseTraits tests/Unit/CategoryTest.php
// Metodo especifico de uma classe - vendor/bin/phpunit --filter CategoryTest::testIfUseTraits
class GenreTest extends TestCase
{

    private $genre;

    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = new Genre();
    }

    public function testIfUseTraits()
    {
        //Esse metodo ira testar as CLASSES que devem exister
        $traits = [
            SoftDeletes::class,
            Uuid::class
        ];

        //Aqui eu pego as classes q ela usa, porem so as keys para comparar sem chaves
        $genreTraits = array_keys(class_uses(Genre::class));
        $this->assertEquals($traits, $genreTraits);
    }

    public function testFillableAttribute()
    {
        //Vou comparar a variavel criada com os campos existem em $fillable da classe Genre
        // $this->assertTrue(true);
        $fillable = ['name', 'is_active'];
        $this->assertEquals($fillable, $this->genre->getFillable());
    }

    //Esse metodo abaixo poderia ser utiliaszdo o assertCanonico
    public function testDatesAttribute()
    {
        //Esse metodo Verifica a quantidade de campos de data existentes

        $dates = ['deleted_at', 'created_at', 'updated_at'];
        $genre = new Genre();
        // dd($genre->getDates(), $dates);

        //Deu erro por conta dos indices dos arrays
        // $this->assertEquals($dates, $genre->getDates());

        foreach ($dates as $date) {
            $this->assertContains($date, $genre->getDates());
        }

        $this->assertCount(count($dates), $genre->getDates());
    }

    public function testCasts()
    {
        $casts = ['id' => 'string', 'is_active' => 'boolean'];
        $genre = new Genre();
        $this->assertEquals($casts, $genre->getCasts());
    }

    public function testIncrementing()
    {
        $genre = new Genre();
        $this->assertFalse($genre->incrementing);
    }


}
