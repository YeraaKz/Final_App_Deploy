<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\Table(name: '`items`')]
class Item
{
    public function __construct(ItemsCollection $collection)
    {
        $this->collection = $collection;
        $this->attributes = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likes= new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column()]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 3, max: 100)]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: ItemsCollection::class, inversedBy: 'items')]
    private ?ItemsCollection $collection = null;

    #[ORM\OneToMany(targetEntity: ItemAttributeValue::class, mappedBy: 'item', cascade: ['persist'], orphanRemoval: true)]
    private Collection $attributes;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'items')]
    #[ORM\JoinTable(name: 'item_tag')]
    private Collection $tags;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'item', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $comments;

    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'item', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $likes;

    #[ORM\Column(name: 'created_at', type: 'datetime')]
    private ?DateTime $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCollection(): ?ItemsCollection
    {
        return $this->collection;
    }

    public function setCollection(?ItemsCollection $collection): void
    {
        $this->collection = $collection;
    }

    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function setAttributes(Collection $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags(Collection $tags): void
    {
        $this->tags = $tags;
    }


    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->getItems()->add($this);
        }
        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->getItems()->removeElement($this);
        }
        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function setComments(Collection $comments): void
    {
        $this->comments = $comments;
    }

    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function setLikes(Collection $likes): void
    {
        $this->likes = $likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setItem($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            if ($like->getItem() === $this) {
                $like->setItem(null);
            }
        }

        return $this;
    }

    public function isLikedByUser(User $user): bool
    {
        foreach ($this->likes as $like) {
            if ($like->getUser() === $user) {
                return true;
            }
        }

        return false;
    }
}
