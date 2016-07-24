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
        $response->headers->set("Content-Type", "application/json");
        $response->setContent($this->manager->serialize($resource, 'json'));
    }
}
