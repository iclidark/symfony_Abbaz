<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegister(): void
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Créer un compte');
    }

    public function testRegistrationForm(): void
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $client->submitForm('Créer mon compte', [
            'registration_form[email]' => 'test@example.com',
            'registration_form[plainPassword]' => 'password',
            'registration_form[agreeTerms]' => true,
            'registration_form[firstname]' => 'John',
            'registration_form[lastname]' => 'Doe',
        ]);

        self::assertResponseRedirects('/');
        $client->followRedirect();

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Bienvenue sur votre gestionnaire de tâches');
    }
}
