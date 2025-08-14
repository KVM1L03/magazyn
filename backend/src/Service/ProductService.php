<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\ProductCreateRequest;
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

    public function createProduct($name, $quantity): Product
    {
        if (!is_string($name) && $name !== null) {
            throw new \InvalidArgumentException('Nazwa produktu musi być tekstem');
        }
        
        if (!is_numeric($quantity) && $quantity !== null) {
            throw new \InvalidArgumentException('Ilość musi być liczbą');
        }

        $name = (string) ($name ?? '');
        $quantity = (int) ($quantity ?? 0);
        
        $dto = new ProductCreateRequest($name, $quantity);

        $errors = $this->validator->validate($dto);
        
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            throw new \InvalidArgumentException(implode(', ', $errorMessages));
        }

        $product = new Product();
        $product->setName($dto->name);
        $product->setCurrentQuantity($dto->quantity);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function updateStock(Product $product, $amount): void
    {
        if (!is_numeric($amount)) {
            throw new \InvalidArgumentException('Wartość amount musi być liczbą');
        }
        
        $amount = (int) $amount;
        $newQuantity = $product->getCurrentQuantity() + $amount;
        
        if ($newQuantity < 0) {
            throw new \InvalidArgumentException('Ilość produktu nie może być ujemna');
        }
        
        $product->setCurrentQuantity($newQuantity);
        $this->entityManager->flush();
    }


   
}