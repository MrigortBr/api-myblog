<?php
namespace App\Service;

use App\Models\Categories;
use App\Utils\RequestUtility;



class CategoryService{

    protected $requestUtility;

    public function __construct( RequestUtility $requestUtility){
        $this->requestUtility =  $requestUtility;
    }

    public static function findOrCreateCategory(string $name): int{
        $name = strtolower($name);

        // Tenta encontrar a categoria pelo nome
        $category = Categories::where('name', 'LIKE', $name)->first();

        if ($category) {
            // Retorna o ID da categoria existente
            return $category->id;
        }

        // Cria uma nova categoria e retorna o ID
        $newCategory = Categories::create(['name' => $name]);

        return $newCategory->id;
    }

    public static function findCategory(string $name){
        return Categories::where('name', 'LIKE', $name)->first();
    }

    public static function findCategoryById(string $id){
        return Categories::find($id);
    }

}
