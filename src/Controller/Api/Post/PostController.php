<?php

namespace App\Controller\Api\Post;

use App\Entity\Post;
use App\Exception\ApiException;
use App\Repository\IRepository\PostRepositoryInterface;
use App\Resource\PaginationResource;
use App\Resource\SuccessResource;
use App\Services\PostService;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PostController extends AbstractController
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository,
        private readonly PostService $postService,
        private readonly SerializerInterface $serializer
    ){
    }

    #[Route('/api/posts', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $this->postService->parseQueryParam($request->query->get('page'), 1);
        $limit = $this->postService->parseQueryParam($request->query->get('limit'), 15);

        $pagination = $this->postRepository->getAll($page, $limit);
        $paginationData = new PaginationResource($pagination);

        $resource = new SuccessResource(
            message: 'Posts',
            data: $this->serializer->normalize($pagination->getItems()),
            pagination: $paginationData->toArray()
        );
        return $resource->toJsonResponse();
    }

    /**
     * @throws ApiException
     */
    #[Route('/api/posts/{id}', methods: ['GET'])]
    public function show(int $id): Response
    {
        $post = $this->postRepository->find($id);
        if (!$post) {
            throw new ApiException("Post with ID $id not found.", 404);
        }
        $resource = new SuccessResource(
            message: 'Post found successfully',
            data: $this->serializer->normalize($post)
        );

        return $resource->toJsonResponse();
    }

    /**
     * @throws RandomException
     */
    #[Route('/api/posts', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $post = new Post();
        $post = $this->postService->makePost($post, $request->request->all());
        $resource = new SuccessResource(
            message: 'Post found successfully',
            data: $this->serializer->normalize($post)
        );
        return $resource->toJsonResponse();
    }

    /**
     * @throws ApiException
     * @throws RandomException
     */
    #[Route('/api/posts/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): Response
    {
        // For handling file uploads, it's better to use POST
        // However, if using PUT, ensure multipart/form-data is used
        $post = $this->postRepository->find($id);
        if (!$post) {
            throw new ApiException("Post with ID $id not found.", 404);
        }
        $data = $request->request->all();
        $this->postService->makePost($post, $data);

        $resource = new SuccessResource(
            message: 'Post successfully update',
        );
        return $resource->toJsonResponse();
    }

    /**
     * @throws ApiException
     */
    #[Route('/api/posts/{id}', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $post = $this->postRepository->find($id);
        if (!$post) {
            throw new ApiException("Post with ID $id not found.", 404);
        }
        $this->postService->deletePost($post);
        $resource = new SuccessResource(
            message: 'Post successfully delete',
        );
        return $resource->toJsonResponse();
    }
}
