<?php
namespace App\Controller;

use Corley\Middleware\Annotations as Middleware;
use App\Transformer\GenericUserTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Pagination\Cursor;

/**
 * @Middleware\After(targetClass="App\Serializer\JsonSerializer", targetMethod="serialize")
 * @Middleware\Route("/v1", methods={"GET"})
 */
class UserController
{
    /**
     * @Inject("orm")
     */
    private $manager;

    /**
     * @Inject("userRepository")
     */
    private $userRepository;

    /**
     * @Middleware\Route("/user", methods={"GET"})
     */
    public function indexAction($request)
    {
        $page = $request->request->get("page", 1);

        $users = $this->userRepository->findAll();

        //$cursor = new Cursor($page, $page-1, $page+1, count($users));

        $resource = new Collection($users, new GenericUserTransformer());
        //$resource->setCursor($cursor);

        return $resource;
    }

    /**
     * @Middleware\Route("/user/{id}", methods={"GET"})
     */
    public function getAction($request, $response, $id)
    {
        $users = $this->userRepository->findOneById($id);

        $resource = new Item($users, new GenericUserTransformer());

        return $resource;
    }
}
