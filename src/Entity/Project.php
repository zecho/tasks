<?php
declare(strict_types=1);

namespace App\Entity;

use App\DTO\ProjectDTO;
use App\Enum\ProjectStatus;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[
    ORM\Entity,
    ORM\Table(name: 'project'),
    Gedmo\SoftDeleteable()
]
class Project
{
    #[
        ORM\Id(),
        ORM\Column(name: 'project_id', type: 'integer', unique: true, insertable: false, updatable: false),
        ORM\GeneratedValue(strategy: 'AUTO')
    ]
    private int $id;

    #[ORM\Column(name: 'title', type: 'string', nullable: false)]
    private string $title;

    #[ORM\Column(name: 'description', type: 'string', nullable: false)]
    private string $description;

    #[ORM\Column(name: 'status', type: 'string', enumType: ProjectStatus::class)]
    private ?ProjectStatus $status = ProjectStatus::NEW;

    #[ORM\Column(name: 'start_at', type: 'datetimetz_immutable')]
    private ?\DateTimeInterface $start = null;

    #[ORM\Column(name: 'end_at', type: 'datetimetz_immutable')]
    private ?\DateTimeInterface $end = null;

    #[ORM\Column(name: 'client', type: 'string', nullable: true)]
    private ?string $client = null;

    #[ORM\Column(name: 'company', type: 'string', nullable: true)]
    private ?string $company = null;

    #[ORM\Column(name: 'deleted_at', type: 'datetimetz_immutable', nullable: true)]
    private ?\DateTimeInterface $deletedAt = null;

    public static function createFromDTO(ProjectDTO $dto): Project
    {
        $project = new self();
        $project->setTitle($dto->title);
        $project->setDescription($dto->description);
        $project->setStatus(ProjectStatus::tryFrom($dto->status ?? 'new'));
        $project->setStart($dto->start);
        $project->setEnd($dto->end);
        $project->setCompany($dto->company);
        $project->setClient($dto->client);

        return $project;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getStatus(): ProjectStatus
    {
        return $this->status;
    }

    public function setStatus(ProjectStatus $status): void
    {
        $this->status = $status;
    }

    public function getStart(): \DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): void
    {
        $this->start = $start;
    }

    public function getEnd(): \DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): void
    {
        $this->end = $end;
    }

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(string|null $client): void
    {
        $this->client = $client;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string|null $company): void
    {
        $this->company = $company;
    }

    public function getTarget(): string
    {
        return $this->client ?? $this->company ?? '';
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }
}
