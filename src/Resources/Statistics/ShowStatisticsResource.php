<?php

namespace App\Resources\Statistics;

class ShowStatisticsResource
{
    public int $totalProductCount;
    public int $totalCategoriesCount;
    public float $totalProductsWeightKg;
    public float $avgProductsWeight;
    public array $mostPopularCategories;
    public array $leastPopularCategories;

    public function __construct(
        int $totalProductCount,
        int $totalCategoriesCount,
        float $totalProductsWeightKg,
        float $avgProductsWeight,
        array $mostPopularCategories,
        array $leastPopularCategories
    )
    {
        $this->totalProductCount = $totalProductCount;
        $this->totalCategoriesCount = $totalCategoriesCount;
        $this->totalProductsWeightKg = $totalProductsWeightKg;
        $this->avgProductsWeight = $avgProductsWeight;
        $this->mostPopularCategories = $mostPopularCategories;
        $this->leastPopularCategories = $leastPopularCategories;
    }

    public function toCsvArray(): array
    {
        return count($this->mostPopularCategories) !== 5
            ? []
            : [
            'Общее количество товаров: ,'.$this->totalProductCount,
            'Общее количество категорий: ,'. $this->totalCategoriesCount,
            'Общая масса товаров (кг): ,'. $this->totalProductsWeightKg,
            'Средняя масса товаров: ,'. $this->avgProductsWeight,
            'Топ-5 категорий по количеству товаров: ',
            $this->mostPopularCategories[0]['name'].','.$this->mostPopularCategories[0]['count'],
            $this->mostPopularCategories[1]['name'].','.$this->mostPopularCategories[1]['count'],
            $this->mostPopularCategories[2]['name'].','.$this->mostPopularCategories[2]['count'],
            $this->mostPopularCategories[3]['name'].','.$this->mostPopularCategories[3]['count'],
            $this->mostPopularCategories[4]['name'].','.$this->mostPopularCategories[4]['count'],
            'Топ-5 категорий по количеству товаров (по убыванию): ',
            $this->leastPopularCategories[0]['name'].','.$this->leastPopularCategories[0]['count'],
            $this->leastPopularCategories[1]['name'].','.$this->leastPopularCategories[1]['count'],
            $this->leastPopularCategories[2]['name'].','.$this->leastPopularCategories[2]['count'],
            $this->leastPopularCategories[3]['name'].','.$this->leastPopularCategories[3]['count'],
            $this->leastPopularCategories[4]['name'].','.$this->leastPopularCategories[4]['count'],
        ];
    }
}