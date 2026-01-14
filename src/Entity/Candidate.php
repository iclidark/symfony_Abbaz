<?php

namespace App\Entity;

use App\Repository\CandidateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: CandidateRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Candidate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'First name is required.')]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Last name is required.')]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Email is required.')]
    #[Assert\Email(message: 'The email "{{ value }}" is not a valid email.')]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column]
    private ?bool $hasExperience = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $experienceDetails = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $availabilityDate = null;

    #[ORM\Column(length: 255)]
    private ?string $status = 'draft';

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?bool $isAvailableImmediately = null;

    #[ORM\Column]
    #[Assert\IsTrue(message: 'You must agree to the terms.')]
    private ?bool $consentRGPD = null;
    
    // Getters and setters...

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->hasExperience && empty($this->experienceDetails)) {
            $context->buildViolation('Experience details are required.')
                ->atPath('experienceDetails')
                ->addViolation();
        }

        if (!$this->isAvailableImmediately && empty($this->availabilityDate)) {
            $context->buildViolation('Availability date is required if you are not available immediately.')
                ->atPath('availabilityDate')
                ->addViolation();
        }
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

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

    public function isHasExperience(): ?bool
    {
        return $this->hasExperience;
    }

    public function setHasExperience(bool $hasExperience): static
    {
        $this->hasExperience = $hasExperience;

        return $this;
    }

    public function getExperienceDetails(): ?string
    {
        return $this->experienceDetails;
    }

    public function setExperienceDetails(?string $experienceDetails): static
    {
        $this->experienceDetails = $experienceDetails;

        return $this;
    }

    public function getAvailabilityDate(): ?\DateTimeInterface
    {
        return $this->availabilityDate;
    }

    public function setAvailabilityDate(?\DateTimeInterface $availabilityDate): static
    {
        $this->availabilityDate = $availabilityDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function isIsAvailableImmediately(): ?bool
    {
        return $this->isAvailableImmediately;
    }

    public function setIsAvailableImmediately(bool $isAvailableImmediately): static
    {
        $this->isAvailableImmediately = $isAvailableImmediately;

        return $this;
    }

    public function isConsentRGPD(): ?bool
    {
        return $this->consentRGPD;
    }

    public function setConsentRGPD(bool $consentRGPD): static
    {
        $this->consentRGPD = $consentRGPD;

        return $this;
    }
}
