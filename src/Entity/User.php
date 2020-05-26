<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
 class User implements UserInterface
               
               {
                   /**
                    * @ORM\Id()
                    * @ORM\GeneratedValue()
                    * @ORM\Column(type="integer")
                    */
                   private $id;
               
                   /**
                    * @ORM\Column(type="string", length=255)
                    */
                   private $firstName;
               
                   /**
                    * @ORM\Column(type="string", length=255)
                    */
                   private $lastName;
               
                   /**
                    * @ORM\Column(type="string", length=255)
                    */
                   private $email;
               
                   /**
                    * @ORM\Column(type="string", length=255,nullable=true)
                    */
                   private $picture;
               
                   /**
                    * @ORM\Column(type="string", length=255)
                    */
                   private $hash;
               
               
                    /**
                     * @Assert\EqualTo(propertyPath="hash",message="vous n'est pas correctement confirmer" )
                     */
                    public  $passwordConfirmer;
               
                   /**
                    * @ORM\Column(type="string", length=255)
                    */
                   private $introduction;
               
                   /**
                    * @ORM\Column(type="text")
                    */
                   private $description;
               
                   /**
                    * @ORM\Column(type="string", length=255)
                    */
                   private $slug;
               
                   /**
                    * @ORM\OneToMany(targetEntity="App\Entity\Ad", mappedBy="author")
                    */
                   private $ads;
            
                   /**
                    * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
                    */
                   private $userRoles;
               
                   public function __construct()
                   {
                       $this->ads = new ArrayCollection();
                       $this->userRoles = new ArrayCollection();
                   }
               
                   public function fullname(){
               
                       return"$this->firstName, $this->lastName ";
                   }
               
                   public function getId(): ?int
                   {
                       return $this->id;
                   }
               
                   public function getFirstName(): ?string
                   {
                       return $this->firstName;
                   }
               
                   public function setFirstName(string $firstName): self
                   {
                       $this->firstName = $firstName;
               
                       return $this;
                   }
               
                   public function getLastName(): ?string
                   {
                       return $this->lastName;
                   }
               
                   public function setLastName(string $lastName): self
                   {
                       $this->lastName = $lastName;
               
                       return $this;
                   }
               
                   public function getEmail(): ?string
                   {
                       return $this->email;
                   }
               
                   public function setEmail(string $email): self
                   {
                       $this->email = $email;
               
                       return $this;
                   }
               
                   public function getPicture(): ?string
                   {
                       return $this->picture;
                   }
               
                   public function setPicture(string $picture): self
                   {
                       $this->picture = $picture;
               
                       return $this;
                   }
               
                   public function getHash(): ?string
                   {
                       return $this->hash;
                   }
               
                   public function setHash(string $hash): self
                   {
                       $this->hash = $hash;
               
                       return $this;
                   }
               
                   public function getIntroduction(): ?string
                   {
                       return $this->introduction;
                   }
               
                   public function setIntroduction(string $introduction): self
                   {
                       $this->introduction = $introduction;
               
                       return $this;
                   }
               
                   public function getDescription(): ?string
                   {
                       return $this->description;
                   }
               
                   public function setDescription(string $description): self
                   {
                       $this->description = $description;
               
                       return $this;
                   }
               
                   public function getSlug(): ?string
                   {
                       return $this->slug;
                   }
               
                   public function setSlug(string $slug): self
                   {
                       $this->slug = $slug;
               
                       return $this;
                   }
               
                   /**
                    * @return Collection|Ad[]
                    */
                   public function getAds(): Collection
                   {
                       return $this->ads;
                   }
               
                   public function addAd(Ad $ad): self
                   {
                       if (!$this->ads->contains($ad)) {
                           $this->ads[] = $ad;
                           $ad->setAuthor($this);
                       }
               
                       return $this;
                   }
               
                   public function removeAd(Ad $ad): self
                   {
                       if ($this->ads->contains($ad)) {
                           $this->ads->removeElement($ad);
                           // set the owning side to null (unless already changed)
                           if ($ad->getAuthor() === $this) {
                               $ad->setAuthor(null);
                           }
                       }
               
                       return $this;
                   }
               
               
                   /**
                    * @ORM\PrePersist
                    * @ORM\PreUpdate
                    */
                   public function initSlug(){
                       $slug = new Slugify();
                       $this->slug = $slug->slugify($this->firstName .'-'. $this->lastName);
                   }
               
               
                   public function getRoles(){
                       $roles=$this->userRoles->map(function ($role){
                            return $role->getTitle();
                       })->toArray();
                       $roles[] = "ROLE_USER";
                       dump($roles);
                       return $roles;

                   }
               
                    public function getPassword()
                    {
                        return $this->hash;
                        // TODO: Implement getPassword() method.
                    }
               
                    public function getSalt()
                    {
                        // TODO: Implement getSalt() method.
                    }
               
                    public function getUsername()
                    {
                        return $this->email;
                        // TODO: Implement getUsername() method.
                    }
               
                    public function eraseCredentials()
                    {
                        // TODO: Implement eraseCredentials() method.
                    }
      
                    /**
                     * @return Collection|Role[]
                     */
                    public function getUserRoles(): Collection
                    {
                        return $this->userRoles;
                    }
   
                    public function addUserRole(Role $userRole): self
                    {
                        if (!$this->userRoles->contains($userRole)) {
                            $this->userRoles[] = $userRole;
                            $userRole->addUser($this);
                        }
   
                        return $this;
                    }

                    public function removeUserRole(Role $userRole): self
                    {
                        if ($this->userRoles->contains($userRole)) {
                            $this->userRoles->removeElement($userRole);
                            $userRole->removeUser($this);
                        }

                        return $this;
                    }
                }
