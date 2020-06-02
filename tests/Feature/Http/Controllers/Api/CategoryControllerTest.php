<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Lang;

//Vamos validar as $rules da Controller
class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        //fazer uma requisição e ver uma categoria
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.index'));

        $response->assertStatus(200)
            ->assertJson([$category->toArray()]);
    }

    public function testShow()
    {
        //fazer uma requisição e ver uma categoria
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.show', ['category' => $category->id]));

        $response->assertStatus(200)
            ->assertJson($category->toArray());
    }

    public function testInvalidationData()
    {
        $response = $this->json('POST', route('categories.store'), []);
        //Metodo abstraido abaixo
        $this->assertInvalidationRequired($response);

        //Outro teste Validando 255 caracteres maximo no campo name
        $response = $this->json('POST', route('categories.store'), [
            'name' => str_repeat('a', 256),
            'is_active' => 'a'
        ]);
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);

        // Validar agora na atualizacao Update
        $category = factory(Category::class)->create();
        $response = $this->json('PUT', route('categories.update', ['category' => $category->id]), []);
        //metodo abstraido abaixo
        $this->assertInvalidationRequired($response);

        $response = $this->json(
            'PUT',
            route('categories.update', ['category' => $category->id]),
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
        $response = $this->json('POST', route('categories.store'), ['name' => 'test']);

        $id = $response->json('id');
        $category = Category::find($id);

        $response
            //status de criação
            ->assertStatus(201)
            ->assertJson($category->toArray());

        $this->assertTrue($response->json('is_active'));
        $this->assertNull($response->json('description'));

        //Outro test
        $response = $this->json('POST', route('categories.store'), [
            'name' => 'test',
            'description' => 'description_text',
            'is_active' => false
            ]);

        $response
            ->assertJsonFragment([
                'description' => 'description_text',
                'is_active' => false
            ]);
    }






    public function testUpdate(){
        $category = factory(Category::class)->create([
            'description' => 'description',
            'is_active' => false
        ]);
        $response = $this->json(
            'PUT',
            route('categories.update', [ 'category' => $category->id]),
            [
                'name' => 'test',
                'description' => 'description_text',
                'is_active' => true
                ]);

        $id = $response->json('id');
        $category = Category::find($id);

        $response
            //status de alteração
            ->assertStatus(200)
            ->assertJson($category->toArray())
            ->assertJsonFragment([
                'description' => 'description_text',
                'is_active' => true
            ]);


        $response = $this->json(
            'PUT',
            route('categories.update', [ 'category' => $category->id]),
            [
                'name' => 'test',
                'description' => '',
            ]);

        $response
        ->assertJsonFragment([
            'description' => null
        ]);

        // Testando description como Null mesmo, pois no trecho anterior ela estava como vazio. q o proprio laravel convert pra null

        $category->description = 'test';
        $category->save();

        $response = $this->json(
            'PUT',
            route('categories.update', [ 'category' => $category->id]),
            [
                'name' => 'test',
                'description' => null,
            ]);

        $response
        ->assertJsonFragment([
            'description' => null
        ]);
    }

}
