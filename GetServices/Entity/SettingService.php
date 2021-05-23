<?php

namespace App\Entity;

use App\Repository\SettingServiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SettingServiceRepository::class)
 */
class SettingService
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $field1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $field2;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $field3 = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $field4;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFild1(): ?string
    {
        return $this->field1;
    }

    public function setField1(?string $field1): self
    {
        $this->field1 = $field1;

        return $this;
    }

    public function getField2(): ?bool
    {
        return $this->field2;
    }

    public function setField2(?bool $field2): self
    {
        $this->field2 = $field2;

        return $this;
    }

    public function getField3(): ?array
    {
        return $this->field3;
    }

    public function setField3(?array $field3): self
    {
        $this->field3 = $field3;

        return $this;
    }

    public function getField4(): ?int
    {
        return $this->field4;
    }

    public function setField4(?int $field4): self
    {
        $this->field4 = $field4;

        return $this;
    }
}
