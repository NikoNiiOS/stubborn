<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[Vich\Uploadable]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;


    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $stock_xs = null;

    #[ORM\Column]
    private ?int $stock_s = null;

    #[ORM\Column]
    private ?int $stock_m = null;

    #[ORM\Column]
    private ?int $stock_l = null;

    #[ORM\Column]
    private ?int $stock_xl = null;

    #[ORM\Column]
    private ?bool $homepage = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStockXs(): ?int
    {
        return $this->stock_xs;
    }

    public function setStockXs(int $stock_xs): static
    {
        $this->stock_xs = $stock_xs;

        return $this;
    }

    public function getStockS(): ?int
    {
        return $this->stock_s;
    }

    public function setStockS(int $stock_s): static
    {
        $this->stock_s = $stock_s;

        return $this;
    }

    public function getStockM(): ?int
    {
        return $this->stock_m;
    }

    public function setStockM(int $stock_m): static
    {
        $this->stock_m = $stock_m;

        return $this;
    }

    public function getStockL(): ?int
    {
        return $this->stock_l;
    }

    public function setStockL(int $stock_l): static
    {
        $this->stock_l = $stock_l;

        return $this;
    }

    public function getStockXl(): ?int
    {
        return $this->stock_xl;
    }

    public function setStockXl(int $stock_xl): static
    {
        $this->stock_xl = $stock_xl;

        return $this;
    }

    public function isHomepage(): ?bool
    {
        return $this->homepage;
    }

    public function setHomepage(bool $homepage): static
    {
        $this->homepage = $homepage;

        return $this;
    }



    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updatedAt = $updated_at;

        return $this;
    }
}
