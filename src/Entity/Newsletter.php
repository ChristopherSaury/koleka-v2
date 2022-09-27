<?php

namespace App\Entity;

use App\Repository\NewsletterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewsletterRepository::class)]
class Newsletter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $subscription_email = null;

    #[ORM\Column]
    private ?bool $term_of_use = null;

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
}
