<?php

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $seller_id = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $upvote = [];

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $downvote = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSellerId(): ?int
    {
        return $this->seller_id;
    }

    public function setSellerId(int $seller_id): self
    {
        $this->seller_id = $seller_id;

        return $this;
    }

    public function getUpvote(): array
    {
        return $this->upvote;
    }

    public function setUpvote(?array $upvote): self
    {
        $this->upvote = $upvote;

        return $this;
    }

    public function getDownvote(): array
    {
        return $this->downvote;
    }

    public function setDownvote(?array $downvote): self
    {
        $this->downvote = $downvote;

        return $this;
    }
}
