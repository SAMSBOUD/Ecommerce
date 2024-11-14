<?php

namespace App\Entity;

use App\Entity\Product;
use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use App\Services\TranslationService;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isMega = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'categories')]
    private Collection $products;

    #[ORM\OneToMany(mappedBy: 'fkCategory', targetEntity: Brand::class)]
    private Collection $brands;

    private TranslationService $translationService;
    private array $translatedDescription;

    #[ORM\Column(length: 255)]
    private ?string $name_en = null;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
        $this->products = new ArrayCollection();
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->brands = new ArrayCollection();

    }

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
        $this->setSlug((new Slugify())->slugify($name)); // cré un slug au moment du set. Va en arriere plan modifier le slug. 

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function isIsMega(): ?bool
    {
        return $this->isMega;
    }

    public function setIsMega(?bool $isMega): static
    {
        $this->isMega = $isMega;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            $product->removeCategory($this);
        }

        return $this;
    }

    
    public function __toString(){
        return $this->name;
        // function created because App/Entity/Category could not be converted to string when adding 
        //associationField in ProductCrudController 'categories'. 
    }

    /**
     * @return Collection<int, Brand>
     */
    public function getBrands(): Collection
    {
        return $this->brands;
    }

    public function addBrand(Brand $brand): static
    {
        if (!$this->brands->contains($brand)) {
            $this->brands->add($brand);
            $brand->setFkCategory($this);
        }

        return $this;
    }

    public function removeBrand(Brand $brand): static
    {
        if ($this->brands->removeElement($brand)) {
            // set the owning side to null (unless already changed)
            if ($brand->getFkCategory() === $this) {
                $brand->setFkCategory(null);
            }
        }

        return $this;
    }
    public function setTranslateDescription(): static
    {
        $this->translatedDescription = $this->translationService->translate($this->name);
        return $this;
    }
    public function getTranslatedDescription(): array
    {
        return $this->translatedDescription;
    }

    
    public function translate(): void
    {
        $this->translatedDescription =  $this->translationService->translate($this->name);

       // dd($result);
        //return $result;
    }

    public function getNameEn(): ?string
    {
        return $this->name_en;
    }

    public function setNameEn(string $name_en): static
    {
        $this->name_en = $name_en;

        return $this;
    }
}
