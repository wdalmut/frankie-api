<?php
namespace App\Serializer;

class JsonSerializer
{
    /**
     * @Inject("serializer")
     */
    private $manager;

    public function serialize($request, $response, $resource)
    {
        $manager = $this->manager;

        $data = $manager->createData($resource);

        $response->headers->set("Content-Type", "application/json");
        $response->setContent($data->toJson());
    }
}
