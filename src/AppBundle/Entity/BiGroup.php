<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * BiGroup
 *
 * @ORM\Table(name="bi_groups")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BiGroupRepository")
 */
class BiGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * Many Groups are authored by one user
     *
     * @ORM\ManyToOne(targetEntity="BiUser", inversedBy="groupsAuthored")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
     private $author;


    /**
     * Many Groups have Many Users.
     *
     * @ORM\ManyToMany(targetEntity="BiUser", mappedBy="groups")
     */
    private $users;

    public function __construct() {
        $this->users = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return BiGroup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set User
     *
     * @param BiUser $author
     * @return BiGroup
     */
    public function setUser(BiUser $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get User
     *
     * @return BiUser
     */
    public function getUser()
    {
        return $this->author;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return BiGroup
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return BiGroup
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return BiGroup
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return BiGroup
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Get Users
     *
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param BiUser $user
     */
    public function removeUser(BiUser $user)
    {
        if (false === $this->users->contains($user)) {
            return;
        }
        $this->users->removeElement($user);
        $user->removeGroup($this);
    }

    /**
     * @param BiUser $user
     */
    public function addUser(BiUser $user)
    {
        if (true === $this->users->contains($user)) {
            return;
        }
        $this->users->add($user);
        $user->addGroup($this);
    }
}
