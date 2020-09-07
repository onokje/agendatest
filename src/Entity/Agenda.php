<?php

namespace App\Entity;

use App\Repository\AgendaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AgendaRepository::class)
 */
class Agenda
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $startsAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $endsAt;

    /**
     * @ORM\OneToMany(targetEntity=AgendaEvent::class, mappedBy="agenda")
     */
    private Collection $agendaEvents;

    public function __construct()
    {
        $this->agendaEvents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartsAt(): ?\DateTimeInterface
    {
        return $this->startsAt;
    }

    public function setStartsAt(\DateTimeInterface $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?\DateTimeInterface
    {
        return $this->endsAt;
    }

    public function setEndsAt(\DateTimeInterface $endsAt): self
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    /**
     * @return Collection|AgendaEvent[]
     */
    public function getAgendaEvents(): Collection
    {
        return $this->agendaEvents;
    }

    public function addAgendaEvent(AgendaEvent $agendaEvent): self
    {
        if (!$this->agendaEvents->contains($agendaEvent)) {
            $this->agendaEvents[] = $agendaEvent;
            $agendaEvent->setAgenda($this);
        }

        return $this;
    }

    public function removeAgendaEvent(AgendaEvent $agendaEvent): self
    {
        if ($this->agendaEvents->contains($agendaEvent)) {
            $this->agendaEvents->removeElement($agendaEvent);
            // set the owning side to null (unless already changed)
            if ($agendaEvent->getAgenda() === $this) {
                $agendaEvent->setAgenda(null);
            }
        }

        return $this;
    }
}
