<?php

namespace App\DataFixtures\Provider;

class AppProvider
{

     /**
     * available roles
     * @var array
     */
    private $roles = [
        ["ROLE_USER"],
        ["ROLE_ADMIN"]
    ];
    /**
     * returns a random role
     */
    public function role()
    {
        return $this->roles[array_rand($this->roles)];
    }
}