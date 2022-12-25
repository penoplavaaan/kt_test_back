<?php

namespace App\Util;

trait PagerTrait
{
    /**
     * @param ?int $page
     * @return int
     */
    public function getPage(?int $page = 1): int
    {
        if ($page < 1) {
            $page = 1;
        }

        return $page;
    }

    /**
     * @param ?int $limit
     * @return int
     */
    public function getLimit(?int $limit = 10): int
    {
        if ($limit < 1 || $limit > 150) {
            $limit = 10;
        }

        return (int)$limit;
    }

    /**
     * @param int $page
     * @param ?int $limit
     * @return int
     */
    public function getOffset(int $page, ?int $limit = 15): int
    {
        $offset = 0;
        if ($page != 0 && $page != 1) {
            $offset = ($page - 1) * $limit;
        }

        return (int)$offset;
    }

    /**
     * @param int $total
     * @param ?int $limit
     * @return int
     */
    public function getLastPage(int $total, ?int $limit = 15): int
    {
        return (int)ceil($total/$limit);
    }
}