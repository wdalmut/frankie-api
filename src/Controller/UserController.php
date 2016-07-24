<?php
namespace App\Controller;

use Corley\Middleware\Annotations as Middleware;
use App\Transformer\GenericUserTransformer;
use App\Entity\User;
use Zend\InputFilter\Input;
use Zend\Validator;
use Zend\InputFilter\InputFilter;
use App\Problem\ApiProblem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Middleware\Before(targetClass="App\Parser\Body", targetMethod="extract")
 * @Middleware\After(targetClass="App\Serializer\JsonSerializer", targetMethod="serialize")
 * @Middleware\Route("/v1")
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
     * @Inject("serializer")
     */
    private $serializer;

    /**
     * @Middleware\Route("/user", methods={"GET"})
     */
    public function indexAction($request)
    {
        $page = $request->request->get("page", 1);

        $users = $this->userRepository->findAll();

        return $this->serializer->normalize($users, null, ['groups' => ['user']]);
    }

    /**
     * @Middleware\Route("/user/{id}", methods={"GET"})
     */
    public function getAction($request, $response, $id)
    {
        $user = $this->userRepository->findOneById($id);

        return $this->serializer->normalize($user, null, ['groups' => ['user']]);
    }

    /**
     * @Middleware\Route("/user", methods={"POST"})
     */
    public function createAction(Request $request, Response $response)
    {
        $name = new Input("name");
        $name->getValidatorChain()->attach(new Validator\StringLength(2));

        $inputFilter = new InputFilter();
        $inputFilter->add($name)
            ->setData($request->request);

        if (!$inputFilter->isValid()) {
            return new ApiProblem(406, $inputFilter->getMessages());
        }

        $user = new User();
        $user->setName($inputFilter->getValue("name"));

        $this->manager->persist($user);
        $this->manager->flush();

        return $this->serializer->normalize($user, null, ['groups' => ['user']]);
    }
}
