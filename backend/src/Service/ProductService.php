<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService
{
    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator
    ) {
    }

    public function getAllProducts(): array
    {
        return $this->productRepository->findAll();
    }

    public function getProductById(int $id): ?Product
    {
        return $this->productRepository->find($id);
    }

    public function createProduct(string $name, int $quantity): Product
    {
        $product = new Product();
        $product->setName($name);
        $product->setCurrentQuantity($quantity);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function updateStock(Product $product, int $amount): void
    {
        $newQuantity = $product->getCurrentQuantity() + $amount;
        
        if ($newQuantity < 0) {
            throw new \InvalidArgumentException('Ilość produktu nie może być ujemna po aktualizacji');
        }
        
        $product->setCurrentQuantity($newQuantity);
        $this->entityManager->flush();
    }


   
}