<?php
declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ProductCreateRequest
{
    #[Assert\NotBlank(message: "Nazwa produktu nie może być pusta")]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: "Nazwa produktu musi mieć co najmniej {{ limit }} znaki",
        maxMessage: "Nazwa produktu nie może być dłuższa niż {{ limit }} znaków"
    )]
    public string $name;

    #[Assert\NotNull(message: "Ilość produktu nie może być pusta")]
    #[Assert\Type(
        type: 'integer',
        message: "Ilość musi być liczbą całkowitą"
    )]
    #[Assert\GreaterThanOrEqual(
        value: 0,
        message: "Ilość produktu nie może być ujemna"
    )]
    public int $quantity;

    public function __construct(string $name = '', int $quantity = 0)
    {
        $this->name = $name;
        $this->quantity = $quantity;
    }
}

