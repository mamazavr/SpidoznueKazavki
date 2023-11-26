<?php

class User
{
    private $name;
    private $age;
    private $email;

    private function setName($name)
    {
        $this->name = $name;
    }

    private function setAge($age)
    {
        $this->age = $age;
    }

    private function setEmail($email)
    {
        $this->email = $email;
    }

    public function getAll()
    {
        return [
            'name' => $this->name,
            'age' => $this->age,
            'email' => $this->email,
        ];
    }

    public function __call($method, $args)
    {
        $prefix = substr($method, 0, 3);

        if ($prefix === 'set') {
            $property = lcfirst(substr($method, 3));

            if (property_exists($this, $property)) {
                $this->$property = $args[0];
            } else {
                throw new CustomException("Property '{$property}' does not exist.");
            }
        } else {
            throw new CustomException("Method '{$method}' does not exist.");
        }
    }
}

class CustomException extends Exception
{
}

try {
    $user = new User();
    $user->setName('obamka');
    $user->setAge(9);
    $user->setEmail('mamazavr@typoi.ya');
    $userData = $user->getAll();
    print_r($userData);
} catch (CustomException $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}


