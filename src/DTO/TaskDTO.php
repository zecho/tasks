<?php
declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class TaskDTO
{
    #[Assert\NotBlank(groups: ['create'])]
    public ?string $title = '';

    #[Assert\NotBlank(groups: ['create'])]
    public ?string $description = '';

    #[Assert\NotBlank(groups: ['create'])]
    public ?string $status = null;

    #[Assert\NotBlank(groups: ['create'])]
    public ?\DateTimeInterface $start = null;

    #[Assert\NotBlank(groups: ['create'])]
    public ?\DateTimeInterface $end = null;

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
