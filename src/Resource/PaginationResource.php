<?php

namespace App\Resource;

use Knp\Component\Pager\Pagination\PaginationInterface;

class PaginationResource
{
    private PaginationInterface $pagination;

    public function __construct(PaginationInterface $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * Format the response as a JSON array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'total' => $this->pagination->getTotalItemCount(),
            'page' => $this->pagination->getCurrentPageNumber(),
            'limit' => $this->pagination->getItemNumberPerPage(),
            'pages' => $this->pagination->getPageCount(),
        ];
    }
}