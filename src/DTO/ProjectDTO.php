<?php
declare(strict_types=1);

namespace App\DTO;

use App\Enum\ProjectStatus;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProjectDTO
{
    #[Assert\NotBlank(groups: ['create'])]
    public ?string $title = null;

    #[Assert\NotBlank(groups: ['create'])]
    public ?string $description = null;

    #[
        Assert\Type(type: ProjectStatus::class)
    ]
    public ?string $status = null;

    #[Assert\NotBlank(groups: ['create'])]
    public ?\DateTimeInterface $start = null;

    #[Assert\NotBlank(groups: ['create'])]
    public ?\DateTimeInterface $end = null;

    public ?string $company = null;

    public ?string $client = null;

    public static function create(array $values = []): ProjectDTO
    {
        $dto = new self();

        foreach ($values as $key => $value) {
            if (property_exists($dto, $key)) {
                $dto->{$key} = $value;
            }
        }

        return $dto;
    }

    #[Assert\Callback(groups: ['create'])]
    public function validateClientOrCompanyIsSet(ExecutionContextInterface $context): void
    {
        if (!$this->company && !$this->client) {
            $context
                ->buildViolation('Client or Company should be set.')
                ->addViolation();
        }
    }

    #[Assert\Callback]
    public function validatePositiveDuration(ExecutionContextInterface $context): void
    {
        if (!$this->start || !$this->end) {
            return;
        }

        if ($this->start->diff($this->end)->invert) {
            $context
                ->buildViolation('Ending should be after start.')
                ->atPath('end')
                ->addViolation();
        }
    }
}
