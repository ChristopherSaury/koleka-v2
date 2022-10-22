<?php

namespace App\Entity;

use App\Repository\NewsletterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NewsletterRepository::class)]
class Newsletter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(
        message: 'Vous devez renseigner votre adresse email' 
    )]
    #[ORM\Column(length: 255)]
    private ?string $subscription_email = null;

    #[Assert\NotBlank(
        message: 'Vous devez accepter les conditions d\'utilisation des données' 
    )]
    #[ORM\Column]
    private ?bool $term_of_use = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[Assert\NotBlank(
        message: 'Vous devez renseigner votre prénom' 
    )]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'Le prénom doit faire {{ limit }} caractères minimum',
        maxMessage: 'Le prénom doit faire {{ limit }} caractères maximum',
    )]
    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[Assert\NotBlank(
        message: 'Vous devez renseigner votre nom' 
    )]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'Le nom doit faire {{ limit }} caractères minimum',
        maxMessage: 'Le nom doit faire {{ limit }} caractères maximum',
    )]
    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubscriptionEmail(): ?string
    {
        return $this->subscription_email;
    }

    public function setSubscriptionEmail(string $subscription_email): self
    {
        $this->subscription_email = $subscription_email;

        return $this;
    }

    public function isTermOfUse(): ?bool
    {
        return $this->term_of_use;
    }

    public function setTermOfUse(bool $term_of_use): self
    {
        $this->term_of_use = $term_of_use;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }
}
