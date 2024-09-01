<?php

namespace App\Resource;

use Symfony\Component\HttpFoundation\JsonResponse;

class SuccessResource
{
    private string $message;
    private array $data;
    private array $pagination;

    public function __construct(string $message = '', array $data = [], array $pagination = [])
    {
        $this->message = $message;
        $this->data = $data;
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
            'success' => true,
            'message' => $this->message,
            'data' => $this->data,
            'pagination' => $this->pagination['pagination'] ?? $this->pagination
        ];
    }

    /**
     * Create a JsonResponse with the formatted array.
     *
     * @return JsonResponse
     */
    public function toJsonResponse(): JsonResponse
    {
        return new JsonResponse($this->toArray());
    }
}