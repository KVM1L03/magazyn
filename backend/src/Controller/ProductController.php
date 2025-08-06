<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/products')]
final class ProductController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $products = $em->getRepository(Product::class)->findAll();

        $data = array_map(fn($p) => [
            'id' => $p->getId(),
            'name' => $p->getName(),
            'quantity' => $p->getCurrentQuantity(),
        ], $products);

        return new JsonResponse($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $product = new Product();
        $product->setName($data['name']);
        $product->setCurrentQuantity($data['quantity']);

        $em->persist($product);
        $em->flush();

        return new JsonResponse(['status' => 'created'], 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function detail(Product $product): JsonResponse
    {
        return new JsonResponse([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'quantity' => $product->getCurrentQuantity(),
        ]);
    }

    #[Route('/{id}/update', methods: ['POST'])]
    public function updateStock(Product $product, Request $request, ProductService $productService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $amount = $data['amount'];

        $productService->updateStock($product, $amount);

        return new JsonResponse(['status' => 'updated']);
    }
}
