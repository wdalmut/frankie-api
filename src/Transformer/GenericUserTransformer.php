<?php
namespace App\Transformer;

use Acme\Model\Book;
use League\Fractal;
use App\Entity\User;

class GenericUserTransformer extends Fractal\TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id'      => (int) $user->getId(),
            'name'    => $user->getName(),
        ];
    }
}
