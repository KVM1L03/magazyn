<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\InventoryChange;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function getAllProducts(): array
    {
        return $this->em->getRepository(Product::class)->findAll();
    }

    public function getProductById(int $id): ?Product
    {
        return $this->em->getRepository(Product::class)->find($id);
    }

    public function createProduct(string $name, int $quantity): Product
    {
        $product = new Product();
        $product->setName($name);
        $product->setCurrentQuantity($quantity);

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    public function updateStock(Product $product, int $amount): void
    {
        $change = new InventoryChange();
        $change->setProduct($product);
        $change->setAmount($amount);
        $change->setCreatedAt(new \DateTimeImmutable());

        $product->setCurrentQuantity($product->getCurrentQuantity() + $amount);

        $this->em->persist($change);
        $this->em->persist($product);
        $this->em->flush();
    }

    public function updateProduct(Product $product, array $data): void
    {
        if (isset($data['name'])) {
            $product->setName($data['name']);
        }
        if (isset($data['quantity'])) {
            $product->setCurrentQuantity($data['quantity']);
        }

        $this->em->persist($product);
        $this->em->flush();
    }

    public function deleteProduct(Product $product): void
    {
        $this->em->remove($product);
        $this->em->flush();
    }
}