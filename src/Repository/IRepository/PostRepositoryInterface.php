<?php

namespace App\Repository\IRepository;

use Knp\Component\Pager\Pagination\PaginationInterface;

interface PostRepositoryInterface
{
    public function getAll(): PaginationInterface;
}
