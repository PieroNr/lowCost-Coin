<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use App\Factory\AnswerFactory;
use App\Factory\ProductFactory;
use App\Factory\QuestionFactory;
use App\Factory\TagFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public $pwdHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->pwdHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $userAdmin = new User();
        $userAdmin
            ->setEmail('admin@admin.fr')
            ->setFirstname('Admin')
            ->setLastname('Boy')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $pwd = "admin";

        $hashPwd = $this->pwdHasher->hashPassword(
            $userAdmin,
            $pwd
        );
        $userAdmin->setPassword($hashPwd);

        $manager->persist($userAdmin);
        $manager->flush();



        UserFactory::createMany(10, function () {
            return ['password' => 'password'];
        });
        TagFactory::createMany(10);

        ProductFactory::createMany(20, function () {
            return ['tags' => TagFactory::randomSet(2), 'sellerId' => UserFactory::random()];
        });
        QuestionFactory::createMany(20, function () {
            return ['buyerId' => UserFactory::random(), 'productId' => ProductFactory::random()];
        });
        AnswerFactory::createMany(20, function () {
            return ['question' => QuestionFactory::random(), 'sellerId' => UserFactory::random()];
        });
    }
}
