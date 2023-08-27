<?php
namespace App\Entity\Traits;
use Doctrine\ORM\Mapping as ORM;

trait Timestampable
{
    #[ORM\Column(type:'datetime_immutable', options:['default'=>'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type:'datetime_immutable', options:['default'=>'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $updatedAt = null;



    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }



    
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps()
    {
        if ($this->getCreatedAt() === null) { //verifier si getcreted existe si oui creer nv time sinon modifier
            $this->setCreatedAt(new \DateTimeImmutable);
        }
        $this->setUpdatedAt(new \DateTimeImmutable);//des q on ecree entite y a resporistry qui s ecrit automati
    }
}