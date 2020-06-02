<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Lang;

class GenreControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        //fazer uma requisição e ver uma genero
        $genre = factory(Genre::class)->create();
        $response = $this->get(route('genres.index'));

        $response->assertStatus(200)
            ->assertJson([$genre->toArray()]);
    }

    public function testShow()
    {
        //fazer uma requisição e ver uma genero
        $genre = factory(Genre::class)->create();
        $response = $this->get(route('genres.show', ['genre' => $genre->id]));

        $response->assertStatus(200)
            ->assertJson($genre->toArray());
    }

    public function testInvalidationData()
    {
        $response = $this->json('POST', route('genres.store'), []);
        //Metodo abstraido abaixo
        $this->assertInvalidationRequired($response);

        //Outro teste Validando 255 caracteres maximo no campo name
        $response = $this->json('POST', route('genres.store'), [
            'name' => str_repeat('a', 256),
            'is_active' => 'a'
        ]);
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);

        // Validar agora na atualizacao Update
        $genre = factory(Genre::class)->create();
        $response = $this->json('PUT', route('genres.update', ['genre' => $genre->id]), []);
        //metodo abstraido abaixo
        $this->assertInvalidationRequired($response);

        $response = $this->json(
            'PUT',
            route('genres.update', ['genre' => $genre->id]),
            [
                'name' => str_repeat('a', 256),
                'is_active' => 'a'
            ]
        );
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);

    }

    protected function assertInvalidationRequired(TestResponse $response){
        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name'])
        //verificar se o is_active NÂO está presente entre os campos que são inválidos
        ->assertJsonMissingValidationErrors(['is_active'])
        ->assertJsonFragment([Lang::get('validation.required', ['attribute' => 'name'])]);
    }

    protected function assertInvalidationMax(TestResponse $response){
        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name'])
        ->assertJsonFragment([Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255])]);
    }

    protected function assertInvalidationBoolean(TestResponse $response){
        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors(['is_active'])
        ->assertJsonFragment([Lang::get('validation.boolean', ['attribute' => 'is active'])]);
    }

    public function testStore(){
        $response = $this->json('POST', route('genres.store'), ['name' => 'test']);

        $id = $response->json('id');
        $genre = Genre::find($id);

        $response
            //status de criação
            ->assertStatus(201)
            ->assertJson($genre->toArray());

        $this->assertTrue($response->json('is_active'));


        //Outro test
        $response = $this->json('POST', route('genres.store'), [
            'name' => 'test',
            'is_active' => false
            ]);

        $response
            ->assertJsonFragment([
                'is_active' => false
            ]);
    }

    public function testUpdate(){
        $genre = factory(Genre::class)->create([
            'is_active' => false
        ]);
        $response = $this->json(
            'PUT',
            route('genres.update', [ 'genre' => $genre->id]),
            [
                'name' => 'test',
                'is_active' => true
                ]);

        $id = $response->json('id');
        $genre = Genre::find($id);

        $response
            //status de alteração
            ->assertStatus(200)
            ->assertJson($genre->toArray())
            ->assertJsonFragment([
                'is_active' => true
            ]);
    }

    public function testDestroy(){
        $genre = factory(Genre::class)->create();
        $response = $this->json('DELETE', route('genres.destroy', ['genre' => $genre->id]));
        $response->assertStatus(204);
        $this->assertNull(Genre::find($genre->id));
        $this->assertNotNull(Genre::withTrashed()->find($genre->id));
    }
}
