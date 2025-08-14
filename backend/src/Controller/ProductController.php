<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use App\Trait\ValidationErrorHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/products')]
final class ProductController extends AbstractController
{
    use ValidationErrorHandler;

    public function __construct(
        private ProductService $productService
    ) {
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse([
                'errors' => [
                    [
                        'property' => 'json',
                        'message' => 'Niepoprawny format JSON'
                    ]
                ],
                'status' => 'validation_error'
            ], 422);
        }

        try {
            $product = $this->productService->createProduct(
                $data['name'] ?? null,
                $data['quantity'] ?? null
            );

            return new JsonResponse([
                'product' => [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'quantity' => $product->getCurrentQuantity(),
                ],
                'status' => 'created'
            ], 201);
            
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse([
                'errors' => [
                    [
                        'property' => 'validation',
                        'message' => $e->getMessage()
                    ]
                ],
                'status' => 'validation_error'
            ], 422);
        }
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
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse([
                'errors' => [
                    [
                        'property' => 'json',
                        'message' => 'Niepoprawny format JSON'
                    ]
                ],
                'status' => 'validation_error'
            ], 422);
        }

        try {
            $this->productService->updateStock($product, $data['amount'] ?? null);

            return new JsonResponse([
                'product' => [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'quantity' => $product->getCurrentQuantity(),
                ],
                'status' => 'stock updated'
            ]);
            
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse([
                'errors' => [
                    [
                        'property' => 'validation',
                        'message' => $e->getMessage()
                    ]
                ],
                'status' => 'validation_error'
            ], 422);
        }
    }
}