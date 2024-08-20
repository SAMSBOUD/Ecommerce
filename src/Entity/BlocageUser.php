<?php
// src/Entity/BlocageUser.php
namespace App\Entity;
use App\Entity\User;
use App\Repository\BlocageUserRepository;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlocageUserRepository::class)]
//[ORM\Table(name: '`order`')]
//[ORM\Entity]
class BlocageUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'blocages')]
    private $user;

    #[ORM\Column(type: 'datetime')]
    private $blockedUntil;

    #[ORM\Column(type: 'string', length: 45)]
    private $ipAddress;

    // Getters et setters

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBlockedUntil(): ?\DateTimeInterface
    {
        return $this->blockedUntil;
    }

    public function setBlockedUntil(\DateTimeInterface $blockedUntil): static
    {
        $this->blockedUntil = $blockedUntil;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

}