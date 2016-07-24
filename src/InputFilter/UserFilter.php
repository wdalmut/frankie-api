<?php
namespace App\InputFilter;

use Zend\InputFilter\Input;
use Zend\Validator;
use Zend\InputFilter\InputFilter;

class UserFilter extends InputFilter
{
    public function __construct()
    {
        $name = new Input("name");
        $name->getValidatorChain()->attach(new Validator\StringLength(2));

        $this->add($name);
    }
}
