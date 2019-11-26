<?php

declare(strict_types=1);

namespace Statistics\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="\Statistics\Infrastructure\Repository\CampaignRepository")
 * @ORM\Table(name="ad_campaign")
 */
class Campaign
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Advertiser", inversedBy="campaigns")
     */
    protected $advertiser;

    /**
     * @ORM\OneToMany(targetEntity="Banner", mappedBy="campaign")
     */
    protected $banners;

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
        $this->banners = new ArrayCollection;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAdvertiser(): Advertiser
    {
        return $this->advertiser;
    }

    public function setAdvertiser(Advertiser $advertiser): self
    {
        $this->advertiser = $advertiser;
        return $this;
    }

    public function getBanners(): Collection
    {
        return $this->banners;
    }

    public function setBanners(Collection $banners): self
    {
        $this->banners = $banners;
        return $this;
    }

    public function addBanner(Banner $banner): self
    {
        if (!$this->banners->contains($banner)) {
            $this->banners->add($banner);
        }

        return $this;
    }

    public function removeBanner(Banner $banner): self
    {
        $this->banners->removeElement($banner);
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