<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //Apenas testando a criacao de um registro na base e mostrando que a cada test,
    // a base de dados estará sendo limpada!

    // public function testExample()
    // {
    //     Category::create([
    //         'name' => 'teste1'
    //         ]);
    //         echo "Bruno LEAL ----------------";
    //     dump(Category::all());
    // }

    // public function testExample1()
    // {
    //     dd(Category::all());
    // }


    public function testeList()
    {
        factory(Category::class, 1)->create();
        //Usando a factory acima para criar
        // $category = Category::create([
        //     'name' => 'teste1'
        // ]);

        $categories = Category::all();
        $this->assertCount(1, $categories);
        $categoryKey = array_keys($categories->first()->getAttributes());
        //So com assertEquals ele da erro com problema de indice(keys)
        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $categoryKey
        );
    }

    public function testCreate()
    {
        //Levando em consideracao os campos da migrate vamos testar:
        //[1] - uuid
        //[2] - name ta preenchido
        //[3] - description é nulo
        //[4] - is_active recebe valor true como default

        $category = Category::create([
            'name' => 'teste1'
        ]);
        //O que faz esse metodo refresh?Aula 5min24 - teste criacao categoria
        $category->refresh();

        $this->assertEquals(36, strlen($category->id));
        $this->assertEquals('teste1', $category->name);
        $this->assertNull($category->description);

        // Para nao ter que usar o bool aqui, alteramos direto na Model de Category is_active => boolean
        // $this->assertTrue((bool)$category->is_active);
        $this->assertTrue($category->is_active);

        //Outro test agora
        $category = Category::create([
            'name' => 'teste1',
            'description' => null
        ]);
        $this->assertNull($category->description);

        //Outro test agora
        $category = Category::create([
            'name' => 'teste1',
            'description' => 'test_description'
        ]);
        $this->assertEquals('test_description', $category->description);

        //Outro test agora
        $category = Category::create([
            'name' => 'teste1',
            'is_active' => false
        ]);
        $this->assertFalse($category->is_active);

        //Outro test agora
        $category = Category::create([
            'name' => 'teste1',
            'is_active' => true
        ]);
        $this->assertTrue($category->is_active);
    }


    public function testUpdate()
    {
        $category = factory(Category::class)->create([
            'description' => 'test_description'
        ]);

            $data = [
                'name' => 'test_name_updated',
                'description' => 'test_description_updated',
                'is_active' => true
            ];

        $category->update($data);
        foreach($data as $key => $value){
            $this->assertEquals($value, $category->{$key});

        }

    }

    public function testDelete(){
        $category = factory(Category::class)->create();
        $category->delete();
        $this->assertNull(Category::find($category->id));

        $category->restore();
        $this->assertNotNull(Category::find($category->id));
    }
}
