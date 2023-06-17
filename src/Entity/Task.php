<?php
declare(strict_types=1);

namespace App\Entity;

use App\Enum\ProjectStatus;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity,
    ORM\Table(name: 'task')
]
class Task
{
    #[
        ORM\Id(),
        ORM\Column(name: 'task_id', type: 'integer', unique: true, insertable: false, updatable: false),
        ORM\GeneratedValue(strategy: 'AUTO')
    ]
    private readonly int $id;

    #[
        ORM\ManyToOne(targetEntity: Project::class),
        ORM\JoinColumn(name: 'project_id', referencedColumnName: 'project_id', onDelete: 'CASCADE')
    ]
    private Project $project;

    #[ORM\Column(name: 'title', type: 'string')]
    private string $title;

    #[ORM\Column(name: 'description', type: 'text')]
    private string $description;

    #[ORM\Column(name: 'status', type: 'string', enumType: ProjectStatus::class)]
    private ProjectStatus $status;

    #[ORM\Column(name: 'start_at', type: 'datetimetz_immutable')]
    private \DateTimeInterface $start;

    #[ORM\Column(name: 'end_at', type: 'datetimetz_immutable')]
    private \DateTimeInterface $end;

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): void
    {
        $this->project = $project;
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

    public function getId(): ?int
    {
        return $this->id;
    }
}
