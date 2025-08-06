<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\InventoryChange;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

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
}
