<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\ProductCreateRequest;
use App\DTO\UpdateStockRequest;
use App\Entity\Product;
use App\Service\ProductService;
use App\Trait\ValidationErrorHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/products')]
final class ProductController extends AbstractController
{
    use ValidationErrorHandler;

    public function __construct(
        private ProductService $productService,
        private ValidatorInterface $validator
    ) {
    }

    #[Route('', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] ProductCreateRequest $request
    ): JsonResponse {
        try {
            $product = $this->productService->createProduct(
                $request->name,
                $request->quantity
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
    public function updateStock(
        Product $product, 
        #[MapRequestPayload] UpdateStockRequest $request
    ): JsonResponse {
        try {
            $this->productService->updateStock($product, $request->amount);

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