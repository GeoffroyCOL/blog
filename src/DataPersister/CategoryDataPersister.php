<?php

namespace App\DataPersister;

use App\Entity\Category;
use App\Service\CategoryService;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

final class CategoryDataPersister implements ContextAwareDataPersisterInterface
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Category;
    }

    public function persist($data, array $context = [])
    {
        $category = $this->categoryService->persist($data);
        return $category;
    }

    public function remove($data, array $context = [])
    {
        $category = $this->categoryService->delete($data);
        return $category;
    }
}
