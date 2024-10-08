<?php

namespace App\Entity;

use App\Repository\SettingRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $website_NAME = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $currency = null;

    #[ORM\Column(nullable: true)]
    private ?int $taxe_rate = null;

    #[ORM\Column(length: 255)]
    private ?string $logo = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $code_postal = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\Column(nullable: true)] 
    private ?\DateTimeImmutable $updated_at = null;

/*     public function __constructs(Type $var = null) {
        $this->setUpdatedAt(new \DateTimeImmutable()); 
    } */

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facebookLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $youTubeLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $InstagramLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail = null;

    public function __construct(Type $var = null) {
        $this->setCreatedAt(new \DateTimeImmutable()); 
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getWebsiteNAME(): ?string
    {
        return $this->website_NAME;
    }

    public function setWebsiteNAME(string $website_NAME): static
    {
        $this->website_NAME = $website_NAME;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getTaxeRate(): ?int
    {
        return $this->taxe_rate;
    }

    public function setTaxeRate(?int $taxe_rate): static
    {
        $this->taxe_rate = $taxe_rate;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(string $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFacebookLink(): ?string
    {
        return $this->facebookLink;
    }

    public function setFacebookLink(?string $facebookLink): static
    {
        $this->facebookLink = $facebookLink;

        return $this;
    }

    public function getYouTubeLink(): ?string
    {
        return $this->youTubeLink;
    }

    public function setYouTubeLink(?string $youTubeLink): static
    {
        $this->youTubeLink = $youTubeLink;

        return $this;
    }

    public function getInstagramLink(): ?string
    {
        return $this->InstagramLink;
    }

    public function setInstagramLink(?string $InstagramLink): static
    {
        $this->InstagramLink = $InstagramLink;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }
}
