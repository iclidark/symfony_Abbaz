<?php

namespace App\Form;

use App\Entity\Candidate;
use App\Form\PersonalInformationType;
use App\Form\ExperienceType;
use App\Form\AvailabilityType;
use App\Form\ConsentType;

class CandidateApplicationFlow
{
    private $steps = [];

    public function __construct() {
        $this->steps = $this->getStepsConfig();
    }

    public function getStepsConfig(): array
    {
        return [
            1 => [
                'label' => 'Personal Information',
                'form_type' => PersonalInformationType::class,
            ],
            2 => [
                'label' => 'Experience',
                'form_type' => ExperienceType::class,
                'is_conditional' => true,
            ],
            3 => [
                'label' => 'Availability',
                'form_type' => AvailabilityType::class,
            ],
            4 => [
                'label' => 'Consent',
                'form_type' => ConsentType::class,
            ],
        ];
    }

    public function getStep(int $step): ?array
    {
        return $this->steps[$step] ?? null;
    }

    public function getFormTypeForStep(int $step): ?string
    {
        return $this->getStep($step)['form_type'] ?? null;
    }
    
    public function getTotalSteps(): int
    {
        return count($this->steps);
    }
}
