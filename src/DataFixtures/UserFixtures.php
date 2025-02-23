<?php

namespace App\DataFixtures;
 
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
 
class UserFixtures extends Fixture 
{    
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    
    public const USER_REFERENCE = 'User';

    public function load(ObjectManager $manager)
    {
        $dataUsers = [
            ["email" => "admin@gmail.com", "firstname" => "Mikail", "lastname" => "ER", "roles" => ["ROLE_ADMIN"], "password" => "emikail"],
            ["email" => "manager@gmail.com", "firstname" => "Iliass", "lastname" => "FERCHACH", "roles" => ["ROLE_MANAGER"], "password" => "filiass"],
            ["email" => "user@gmail.com", "firstname" => "Noa", "lastname" => "BOUGUERREA", "roles" => ["ROLE_USER"], "password" => "bnoa"],
        ];

        foreach ($dataUsers as $key => $dataUser) {
            $user = new User();
            $user->setEmail($dataUser["email"]);
            $user->setFirstName($dataUser["firstname"]);
            $user->setLastname($dataUser["lastname"]);
            $user->setRoles($dataUser["roles"]);
            $user->setPassword($this->passwordHasher->hashPassword($user, $dataUser['password']));

                               
            $manager->persist($user);
            $this->addReference(self::USER_REFERENCE . '_' . $key, $user);

        }
 
        $manager->flush();
    }
}