<?php

namespace Scc\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class BlogPost {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Scc\Entity\Tag", mappedBy="blogPost", cascade={"persist"})
     */
    protected $tags;

    /**
     * @ORM\Column(type="integer")
     */
    protected $title;

    /**
     * Never forget to initialize all your collections !
     */
    public function __construct() {
        $this->tags = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param Collection $tags
     */
    public function addTags(Collection $tags) {
        foreach ($tags as $tag) {
            $tag->setBlogPost($this);
            $this->tags->add($tag);
        }
    }

    /**
     * @param Collection $tags
     */
    public function removeTags(Collection $tags) {
        foreach ($tags as $tag) {
            $tag->setBlogPost(null);
            $this->tags->removeElement($tag);
        }
    }

    /**
     * @return Collection
     */
    public function getTags() {
        return $this->tags;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
    }

}