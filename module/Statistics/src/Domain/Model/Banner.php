<?php

declare(strict_types=1);

namespace Statistics\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="\Statistics\Infrastructure\Repository\BannerRepository")
 * @ORM\Table(name="ad_banner")
 */
class Banner
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Campaign", inversedBy="banners")
     */
    protected $campaign;

    /**
     * @ORM\OneToMany(targetEntity="Statistics", mappedBy="banner")
     */
    protected $statistics;

    /**
     * @ORM\Column(type="integer", name="origin")
     */
    protected $origin;

    /**
     * @ORM\Column(type="string", name="description")
     */
    protected $description;

    public function __construct(int $origin, string $description)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->origin = $origin;
        $this->description = $description;
        $this->statistics = new ArrayCollection;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCampaign(): Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(Campaign $campaign): self
    {
        $this->campaign = $campaign;
        return $this;
    }

    public function getStatistics(): Collection
    {
        return $this->statistics;
    }

    public function setStatistics(Collection $statistics): self
    {
        $this->statistics = $statistics;
        return $this;
    }

    public function addStatistics(Statistics $statistic): self
    {
        if (!$this->statistics->contains($statistic)) {
            $this->statistics->add($statistic);
        }

        return $this;
    }

    public function removeStatistics(Statistics $statistic): self
    {
        $this->statistics->removeElement($statistic);
        return $this;
    }

    public function getOrigin(): int
    {
        return $this->origin;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
}