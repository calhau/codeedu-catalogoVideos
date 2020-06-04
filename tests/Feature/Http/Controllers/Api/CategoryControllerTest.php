<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lang;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

//Vamos validar as $rules da Controller
class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    private $category;

    protected function setUp(): void {
        parent::setUp();
        $this->category = factory(Category::class)->create();
    }

    public function testIndex()
    {
        //fazer uma requisição e ver uma categoria
        $response = $this->get(route('categories.index'));

        $response->assertStatus(200)
            ->assertJson([$this->category->toArray()]);
    }

    public function testShow()
    {
        //fazer uma requisição e ver uma categoria
        $response = $this->get(route('categories.show', ['category' => $this->category->id]));

        $response->assertStatus(200)
            ->assertJson($this->category->toArray());
    }

    public function testInvalidationData()
    {
        $data = [
            'name' => ''
        ];
        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'required');

        //RETIRADO DEPOIS DA REFATORACAO COM TESTVALIDATION-ASSERTINVALIDATIONINSTOREACTION()
        // $response = $this->json('POST', route('categories.store'), []);
        // //Metodo abstraido abaixo
        // $this->assertInvalidationRequired($response);


        //Outro teste
        $data = [
            'name' => str_repeat('a', 256),
        ];
        $this->assertInvalidationInStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationInUpdateAction($data, 'max.string', ['max' => 255]);

        //Outro teste
        $data = [
            'is_active' => 'a'
        ];
        $this->assertInvalidationInStoreAction($data, 'boolean');
        $this->assertInvalidationInUpdateAction($data, 'boolean');


        //RETIRADO DEPOIS DA REFATORACAO COM TESTVALIDATION-ASSERTINVALIDATIONINSTOREACTION()
        // //Outro teste Validando 255 caracteres maximo no campo name
        // $response = $this->json('POST', route('categories.store'), [
        //     'name' => str_repeat('a', 256),
        //     'is_active' => 'a'
        // ]);
        // $this->assertInvalidationMax($response);
        // $this->assertInvalidationBoolean($response);


        // Retirado depois da implementação do assertInvalidationInUpdateAction()
        // Validar agora na atualizacao Update
        // $response = $this->json('PUT', route('categories.update', ['category' => $category->id]), []);
        // //metodo abstraido abaixo
        // $this->assertInvalidationRequired($response);

        // $response = $this->json(
        //     'PUT',
        //     route('categories.update', ['category' => $category->id]),
        //     [
        //         'name' => str_repeat('a', 256),
        //         'is_active' => 'a'
        //     ]
        // );
        // $this->assertInvalidationMax($response);
        // $this->assertInvalidationBoolean($response);

    }

    // //Retirando posterior a implementacao na TestValidations do assertInvalidionFields
    // protected function assertInvalidationRequired(TestResponse $response){

    //     $this->assertInvalidationFields($response, ['name'], 'required');
    //     $response->assertJsonMissingValidationErrors(['is_active']);

    //     // Implementacao antiga, antes de colocar a TRAIT TestValidation
    //     // $response
    //     // ->assertStatus(422)
    //     // ->assertJsonValidationErrors(['name'])
    //     // //verificar se o is_active NÂO está presente entre os campos que são inválidos
    //     // ->assertJsonMissingValidationErrors(['is_active'])
    //     // ->assertJsonFragment([Lang::get('validation.required', ['attribute' => 'name'])]);
    // }

    // protected function assertInvalidationMax(TestResponse $response){
    //     $this->assertInvalidationFields($response, ['name'], 'max.string', ['max' => 255]);
    //     // Implementacao antiga, antes de colocar a TRAIT TestValidation
    //     // $response
    //     // ->assertStatus(422)
    //     // ->assertJsonValidationErrors(['name'])
    //     // ->assertJsonFragment([Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255])]);
    // }

    // protected function assertInvalidationBoolean(TestResponse $response){
    //     $response
    //     ->assertStatus(422)
    //     ->assertJsonValidationErrors(['is_active'])
    //     ->assertJsonFragment([Lang::get('validation.boolean', ['attribute' => 'is active'])]);
    // }

    public function testStore(){

        $data = [
            'name' => 'test'
        ];

      $response = $this->assertStore($data,$data + ['description' => null, 'is_active' => true, 'deleted_at' => null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);

        $data = [
            'name' => 'test',
            'description' => 'description',
            'is_active' => false
        ];

        $this->assertStore($data,$data + ['description' => null, 'is_active' => false]);

        // //Comentado com a criacao do AssertStore na Trait TestSaves.php
        // $response = $this->json('POST', route('categories.store'), ['name' => 'test']);

        // $id = $response->json('id');
        // $category = Category::find($id);

        // $response
        //     //status de criação
        //     ->assertStatus(201)
        //     ->assertJson($category->toArray());

        // $this->assertTrue($response->json('is_active'));
        // $this->assertNull($response->json('description'));

        // //Outro test
        // $response = $this->json('POST', route('categories.store'), [
        //     'name' => 'test',
        //     'description' => 'description_text',
        //     'is_active' => false
        //     ]);

        // $response
        //     ->assertJsonFragment([
        //         'description' => 'description_text',
        //         'is_active' => false
        //     ]);
    }

    public function testUpdate(){
        $this->category = factory(Category::class)->create([
            'description' => 'description',
            'is_active' => false
        ]);
        $data = [
            'name' => 'test',
            'description' => 'description_text',
            'is_active' => true
        ];

        $response = $this->assertUpdate($data, $data + ['deleted_at' => null] );

        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);

        // $response = $this->json(
        //     'PUT',
        //     route('categories.update', [ 'category' => $category->id]),
        //     [
        //         'name' => 'test',
        //         'description' => 'description_text',
        //         'is_active' => true
        //         ]);

        // $id = $response->json('id');
        // $category = Category::find($id);

        // $response
        //     //status de alteração
        //     ->assertStatus(200)
        //     ->assertJson($category->toArray())
        //     ->assertJsonFragment([
        //         'description' => 'description_text',
        //         'is_active' => true
        //     ]);


        $data = [
            'name' => 'test',
            'description' => '',
        ];

        $response = $this->assertUpdate($data, array_merge($data, ['description' => null] ));

        // $response = $this->json(
        //     'PUT',
        //     route('categories.update', [ 'category' => $category->id]),
        //     [
        //         'name' => 'test',
        //         'description' => '',
        //     ]);

        // $response
        // ->assertJsonFragment([
        //     'description' => null
        // ]);

        // // Testando description como Null mesmo, pois no trecho anterior ela estava como vazio. q o proprio laravel convert pra null

        $data['description'] = 'test';
        $this->assertUpdate($data, array_merge($data, ['description' => 'test']));
        // $category->description = 'test';
        // $category->save();


        $data['description'] = null;
        $this->assertUpdate($data, array_merge($data, ['description' => null]));
        // $response = $this->json(
        //     'PUT',
        //     route('categories.update', [ 'category' => $category->id]),
        //     [
        //         'name' => 'test',
        //         'description' => null,
        //     ]);

        // $response
        // ->assertJsonFragment([
        //     'description' => null
        // ]);
    }

    public function testDestroy(){

        $response = $this->json('DELETE', route('categories.destroy', ['category' => $this->category->id]));
        $response->assertStatus(204);
        $this->assertNull(Category::find($this->category->id));
        $this->assertNotNull(Category::withTrashed()->find($this->category->id));
    }

    protected function routeStore(){
        return route('categories.store');
    }

    protected function routeUpdate(){
        return route('categories.update', ['category' => $this->category->id]);
    }

    protected function model(){
        return Category::class;
    }

}
