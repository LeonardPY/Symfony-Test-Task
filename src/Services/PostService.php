<?php

namespace App\Services;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

readonly class PostService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ){
    }

    public function parseQueryParam(?string $param, int $default): int
    {
        // If the parameter is null or an empty string, return the default value
        if ($param === null || $param === '') {
            return $default;
        }

        // Otherwise, return the integer value of the parameter
        return (int) $param;
    }

    /**
     * @throws RandomException
     */
    public function makePost(Post $post, array $data, ?UploadedFile $file = null): Post
    {
        $post->setTitle($data['title'] ?? 'title');
        $post->setSlug($this->makeSlug($data['title'] ?? 'title'));
        $post->setContent($data['content'] ?? 'content');

        if ($file) {
            $post->setImage($this->saveImage($file));
        } else {
            $post->setImage($this->makeImage());
        }

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }

    /**
     * @throws RandomException
     */
    private function makeSlug(string $title): string
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower(trim($title)));

        $randomString = bin2hex(random_bytes(8));

        return $slug . '-' . $randomString;
    }

    private function makeImage(): string
    {
        // Default placeholder image if no file is provided
        return 'default-image.jpg';
    }

    private function saveImage(UploadedFile $file): string
    {
        // upload image return upload file path
        $filesystem = new Filesystem();
        return $this->makeImage();
    }

    public function deletePost(Post $post): void
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }
}