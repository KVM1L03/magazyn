<?php
declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateStockRequest
{
    #[Assert\NotNull(message: "Wartość amount nie może być pusta")]
    #[Assert\Type(
        type: 'integer',
        message: "Amount musi być liczbą całkowitą"
    )]
    public int $amount;

    public function __construct(int $amount = 0)
    {
        $this->amount = $amount;
    }
}