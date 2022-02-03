<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
/**
 * @Vich\Uploadable
 */
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="filePath")
     * @Assert\File(
     *     maxSize="30000K",
     *     mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *          "image/svg"
     *      }
     *
     * )
     */
    private $imageFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $filePath;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'images', cascade: ['persist'])]
    private $productId;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getProductId(): ?Product
    {
        return $this->productId;
    }

    public function setProductId(?Product $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }


    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
