<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class GenreTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */


    public function testeList()
    {
        factory(Genre::class, 1)->create();
        $genres = Genre::all();
        $this->assertCount(1, $genres);
        $genreKey = array_keys($genres->first()->getAttributes());

        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $genreKey
        );
    }

    public function testCreate()
    {
        //Levando em consideracao os campos da migrate vamos testar:
        //[1] - uuid
        //[2] - name ta preenchido
        //[3] - description é nulo
        //[4] - is_active recebe valor true como default

        $genre = Genre::create([
            'name' => 'teste1'
        ]);
        $genre->refresh();

        $this->assertEquals(36, strlen($genre->id));
        $this->assertEquals('teste1', $genre->name);
        $this->assertTrue($genre->is_active);

        //Outro test agora
        $genre = Genre::create([
            'name' => 'teste1',
            'is_active' => false
        ]);
        $this->assertFalse($genre->is_active);

        //Outro test agora
        $genre = Genre::create([
            'name' => 'teste1',
            'is_active' => true
        ]);
        $this->assertTrue($genre->is_active);
    }


    public function testUpdate()
    {
        $genre = factory(Genre::class)->create();

            $data = [
                'name' => 'test_name_updated',
                'is_active' => true
            ];

        $genre->update($data);
        foreach($data as $key => $value){
            $this->assertEquals($value, $genre->{$key});

        }

    }

    public function testDelete(){
        $genre = factory(Genre::class)->create();
        $genre->delete();
        $this->assertNull(Genre::find($genre->id));

        $genre->restore();
        $this->assertNotNull(Genre::find($genre->id));
    }

}
