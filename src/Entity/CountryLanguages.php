<?php

namespace App\Entity;

use App\Repository\CountryLanguagesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: CountryLanguagesRepository::class)]
class CountryLanguages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: 'id')]
    #[JoinColumn(name: 'country_id', referencedColumnName: 'id', nullable: false )]
    private $country_id;

    #[ORM\ManyToOne(targetEntity: Language::class, inversedBy: 'id')]
    #[JoinColumn(name: 'language_id', referencedColumnName: 'id', nullable: false)]
    private $language_id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
