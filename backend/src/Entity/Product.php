<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $Product = null;

    #[ORM\Column]
    private ?int $currentQuantity = null;

    /**
     * @var Collection<int, InventoryChange>
     */
    #[ORM\OneToMany(targetEntity: InventoryChange::class, mappedBy: 'product')]
    private Collection $inventoryChanges;

    public function __construct()
    {
        $this->inventoryChanges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?string
    {
        return $this->Product;
    }

    public function setProduct(string $Product): static
    {
        $this->Product = $Product;

        return $this;
    }

    public function getCurrentQuantity(): ?int
    {
        return $this->currentQuantity;
    }

    public function setCurrentQuantity(int $currentQuantity): static
    {
        $this->currentQuantity = $currentQuantity;

        return $this;
    }

    /**
     * @return Collection<int, InventoryChange>
     */
    public function getInventoryChanges(): Collection
    {
        return $this->inventoryChanges;
    }

    public function addInventoryChange(InventoryChange $inventoryChange): static
    {
        if (!$this->inventoryChanges->contains($inventoryChange)) {
            $this->inventoryChanges->add($inventoryChange);
            $inventoryChange->setProduct($this);
        }

        return $this;
    }

    public function removeInventoryChange(InventoryChange $inventoryChange): static
    {
        if ($this->inventoryChanges->removeElement($inventoryChange)) {
            // set the owning side to null (unless already changed)
            if ($inventoryChange->getProduct() === $this) {
                $inventoryChange->setProduct(null);
            }
        }

        return $this;
    }
}
