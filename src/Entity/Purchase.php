<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
class Purchase
{
    public const STATUS_CREATED = 1;
    public const STATUS_PENDING = 2;
    public const STATUS_ACCEPTED = 3;

    public const PAYMENT_STATUS_PENDING = 1;
    public const PAYMENT_STATUS_PAYED = 2;
    public const PAYMENT_STATUS_ERROR = 3;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $product_id = null;

    #[ORM\Column(length: 255)]
    private ?string $tax_number = null;

    #[ORM\Column(nullable: true)]
    private ?int $coupon_id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $status = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $payment_status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(length: 255)]
    private ?string $payment_type = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $price = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'purchases')]
    private Product $product;

    #[ORM\ManyToOne(targetEntity: Coupon::class, inversedBy: 'purchases')]
    private Coupon $coupon;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function setProductId(int $productId): static
    {
        $this->product_id = $productId;

        return $this;
    }

    public function getTaxNumber(): ?string
    {
        return $this->tax_number;
    }

    public function setTaxNumber(string $taxNumber): static
    {
        $this->tax_number = $taxNumber;

        return $this;
    }

    public function getCouponId(): ?int
    {
        return $this->coupon_id;
    }

    public function setCouponId(?int $couponId): static
    {
        $this->coupon_id = $couponId;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPaymentStatus(): ?int
    {
        return $this->payment_status;
    }

    public function setPaymentStatus(int $paymentStatus): static
    {
        $this->payment_status = $paymentStatus;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->created_at = $createdAt;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->payment_type;
    }

    public function setPaymentType(string $paymentType): static
    {
        $this->payment_type = $paymentType;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCoupon(): ?Coupon
    {
        return $this->coupon;
    }

    public function setCoupon(?Coupon $coupon): self
    {
        $this->coupon = $coupon;

        return $this;
    }
}
