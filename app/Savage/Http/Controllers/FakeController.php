<?php

namespace Savage\Http\Controllers;

class FakeController extends Controller
{
    public function getIndex() {
        $faker = $this->container->faker;
        $user = $this->container->user;

        for($i=0; $i < 10; $i++) {
            $createdUser = $user->create([
                'first_name' => $faker->firstNameMale,
                'last_name' => $faker->lastName,
                'username' => $faker->userName,
                'email' => $faker->safeEmail,
                'password' => $this->container->util->hashPassword('afakepassword123'),
            ]);

            $createdUser->permissions()->create(\Savage\Http\Auth\Permission\UserPermissions::$defaults);
        }

         return $this->container->response
             ->withStatus(200)
             ->withHeader('Content-Type', 'text/html')
             ->write("Added some fake data");
    }
}
