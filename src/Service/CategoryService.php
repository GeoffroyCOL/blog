<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryService
{
    private $manager;
    private $repository;
    private $encoder;

    public function __construct(EntityManagerInterface $manager, CategoryRepository $repository, SluggerInterface $slugger)
    {
        $this->manager = $manager;
        $this->repository = $repository;
        $this->slugger = $slugger;
    }
    
    /**
     * @param  Category $category
     * @return Category
     */
    public function persist(Category $category): Category
    {
        $category->setSlug(strtolower($this->slugger->slug($category->getName())));
        $this->manager->persist($category);
        $this->manager->flush();

        return $category;
    }
    
    /**
     * delete
     *
     * @param  Category $category
     * @return Category
     */
    public function delete(Category $category): Category
    {
        $this->manager->remove($category);
        $this->manager->flush();

        return $category;
    }
}
