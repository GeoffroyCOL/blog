<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Tests\Traits\AssertHasErrors;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{
    use AssertHasErrors;
    
    /**
     * getEntity
     *
     * @return Category
     */
    public function getEntity(): Category
    {
        $category = new Category;
        $category->setName('Catégorie');
        $category->setSlug('categorie');

        return $category;
    }

    /**
     * TestLengtNameCategory
     * Teste si le nombre de caractère de la propriété name est correcte ( >= 4)
     *
     * @return void
     */
    public function testLengtNameCategory(): void
    {
        $category = $this->getentity();
        $category->setName('abc');

        $this->assertHasErrors($category, 1);
    }

    /**
     * TestNotBlankNameCategory
     * Teste si la propriété name n'est pas vide
     *
     * @return void
     */
    public function testNotBlankNameCategory(): void
    {
        $category = $this->getentity();
        $category->setName('');

        $this->assertHasErrors($category, 2);
    }

    /**
     * testUniqueEntity
     * Si la catégorie est unqiue
     *
     * @return void
     */
    public function testUniqueEntity(): void
    {
        $category = $this->getEntity();

        self::bootkernel();
        self::$container->get(CategoryRepository::class);

        $this->assertHasErrors($category, 1);
    }
}