<?php

declare(strict_types=1);

namespace Statistics\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="\Statistics\Infrastructure\Repository\AdvertiserRepository")
 * @ORM\Table(name="ad_advertiser")
 */
class Advertiser
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Campaign", mappedBy="advertiser")
     */
    protected $campaigns;

    /**
     * @ORM\Column(type="integer", name="origin")
     */
    protected $origin;

    /**
     * @ORM\Column(type="string", name="name")
     */
    protected $name;

    public function __construct(int $origin, string $name)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->origin = $origin;
        $this->name = $name;
        $this->campaigns = new ArrayCollection;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCampaigns(): Collection
    {
        return $this->campaigns;
    }

    public function setCampaigns(Collection $campaigns): self
    {
        $this->campaigns = $campaigns;
        return $this;
    }

    public function addCampaign(Campaign $campaign): self
    {
        if (!$this->campaigns->contains($campaign)) {
            $this->campaigns->add($campaign);
        }

        return $this;
    }

    public function removeCampaign(Campaign $campaign): self
    {
        $this->campaigns->removeElement($campaign);
        return $this;
    }

    public function getOrigin(): int
    {
        return $this->origin;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}