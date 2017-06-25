<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedUsers();
        $this->seedOAuthClients();

        $this->em->flush();
    }

    private function seedUsers()
    {
        $userId = \Ramsey\Uuid\Uuid::uuid4();

        $user = new \App\Entities\User(
            $userId,
            'test@test.com',
            'test'
        );

        $this->em->persist($user);
    }

    private function seedOAuthClients()
    {
        $clientId = 'test';
        $clientSecret = 'test';

        $client = new \App\Entities\OAuth\Client(
            $clientId,
            $clientSecret,
            'Test'
        );

        $this->em->persist($client);
    }
}
