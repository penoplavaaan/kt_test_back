<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
class Product
{
    private const WEIGHT_KG = 'kg';
    private const WEIGHT_GR = 'g';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $title;

    /**
     * @ORM\Column(type="string")
     */
    private string $description;

    /**
     * @ORM\Column(type="integer")
     */
    private int $weight;

    /**
     * @ORM\Column(type="integer")
     */
    private int $category_id;

    /**
     * @var Category $category
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private Category $category;

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setWeight(string $weight): void
    {
        $weightArray = explode(' ', $weight);

        $weightValue = $weightArray[0];
        $weightType = $weightArray[1];

        if ($weightType == self::WEIGHT_KG){
            $weightValue *= 1000;
        }

        $this->weight = (int)$weightValue;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }
}