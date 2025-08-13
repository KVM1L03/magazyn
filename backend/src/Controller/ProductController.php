<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/products')]
final class ProductController extends AbstractController
{
    public function __construct(
        private ProductService $productService
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $products = $this->productService->getAllProducts();

        $data = [];
        foreach ($products as $p) {
            $data[] = [
                'id' => $p->getId(),
                'name' => $p->getName(),
                'quantity' => $p->getCurrentQuantity(),
            ];
        }

        return new JsonResponse([
            'products' => $data,
            'count' => count($data)
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $product = $this->productService->createProduct(
            $data['name'],
            $data['quantity']
        );

        return new JsonResponse([
            'product' => [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'quantity' => $product->getCurrentQuantity(),
            ],
            'status' => 'created'
        ], 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function detail(Product $product): JsonResponse
    {
        return new JsonResponse([
            'product' => [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'quantity' => $product->getCurrentQuantity(),
            ],
            'status' => 'success'
        ]);
    }

    #[Route('/{id}/update', methods: ['POST'])]
    public function updateStock(Product $product, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $amount = $data['amount'];

        $this->productService->updateStock($product, $amount);

        return new JsonResponse([
            'product' => [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'quantity' => $product->getCurrentQuantity(),
            ],
            'status' => 'stock updated'
        ]);
    }
}