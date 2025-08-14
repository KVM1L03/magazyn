<?php

namespace App\Controller;

use App\DTO\ProductCreateRequest;
use App\Entity\Product;
use App\Service\ProductService;
use App\Trait\ValidationErrorHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // DTO do walidacji
        $dto = new ProductCreateRequest(
            $data['name'] ?? '',
            $data['quantity'] ?? 0
        );

        // Walidacja DTO
        $errors = $this->validator->validate($dto);

        // Jeśli są błędy walidacji, zwracamy je w formacie z trait
        if (count($errors) > 0) {
            return new JsonResponse([
                'errors' => $this->transformErrors($errors),
                'status' => 'validation_error'
            ], 422);
        }

        // Jeśli walidacja przeszła, tworzymy produkt
        $product = $this->productService->createProduct(
            $dto->name,
            $dto->quantity
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
        
        // Walidacja czy amount jest typu int
        if (!isset($data['amount']) || !is_int($data['amount'])) {
            return new JsonResponse([
                'errors' => [
                    [
                        'property' => 'amount',
                        'message' => 'Wartość amount musi być liczbą całkowitą'
                    ]
                ],
                'status' => 'validation_error'
            ], 422);
        }

        $amount = $data['amount'];

        // Sprawdzamy czy nowa ilość nie będzie na minusie
        $newQuantity = $product->getCurrentQuantity() + $amount;
        if ($newQuantity < 0) {
            return new JsonResponse([
                'errors' => [
                    [
                        'property' => 'amount',
                        'message' => 'Nie można zmniejszyć stanu magazynowego poniżej 0'
                    ]
                ],
                'status' => 'validation_error'
            ], 422);
        }

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