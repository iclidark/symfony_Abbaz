<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $status = 'To Do';

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    /**
     * @var Collection<int, TaskHistory>
     */
    #[ORM\OneToMany(targetEntity: TaskHistory::class, mappedBy: 'task', cascade: ['persist'])]
    private Collection $history;

    public function __construct()
    {
        $this->history = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    // --- Lifecycle Callbacks ---

    #[ORM\PrePersist]
    public function logCreation(): void
    {
        $this->logActivity('created');
    }

    #[ORM\PreUpdate]
    public function logUpdate(): void
    {
        $this->logActivity('updated');
    }

    private function logActivity(string $action): void
    {
        $history = new TaskHistory();
        $history->setTask($this);
        $history->setAction($action);
        $history->setChangedAt(new \DateTimeImmutable());
        // L'auteur de la tâche est considéré comme l'utilisateur effectuant l'action.
        if ($this->getAuthor()) {
            $history->setUser($this->getAuthor());
        }

        $this->history->add($history);
    }
    
    // --- Getters and Setters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, TaskHistory>
     */
    public function getHistory(): Collection
    {
        return $this->history;
    }

    public function addHistory(TaskHistory $history): static
    {
        if (!$this->history->contains($history)) {
            $this->history->add($history);
            $history->setTask($this);
        }

        return $this;
    }

    public function removeHistory(TaskHistory $history): static
    {
        if ($this->history->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getTask() === $this) {
                $history->setTask(null);
            }
        }

        return $this;
    }
}
