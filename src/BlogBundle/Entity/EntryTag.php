<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntryTag
 *
 * @ORM\Table(name="entry_tag", indexes={@ORM\Index(name="fk_entry_tag_entries", columns={"entry_id"}), @ORM\Index(name="fk_entry_tag_tags", columns={"tag_id"})})
 * @ORM\Entity
 */
class EntryTag
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Entry
     *
     * @ORM\ManyToOne(targetEntity="Entry", inversedBy="entryTag")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="entry_id", referencedColumnName="id")
     * })
     */
    private $entry;

    /**
     * @var Tag
     *
     * @ORM\ManyToOne(targetEntity="Tag")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     * })
     */
    private $tag;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set entry
     *
     * @param \BlogBundle\Entity\Entry $entry
     *
     * @return EntryTag
     */
    public function setEntry(\BlogBundle\Entity\Entry $entry = null)
    {
        $this->entry = $entry;

        return $this;
    }

    /**
     * Get entry
     *
     * @return \BlogBundle\Entity\Entry
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * Set tag
     *
     * @param \BlogBundle\Entity\Tag $tag
     *
     * @return EntryTag
     */
    public function setTag(\BlogBundle\Entity\Tag $tag = null)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return \BlogBundle\Entity\Tag
     */
    public function getTag()
    {
        return $this->tag;
    }
}
