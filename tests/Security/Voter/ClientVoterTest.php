<?php

namespace App\Tests\Security\Voter;

use App\Entity\Client;
use App\Security\Voter\ClientVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ClientVoterTest extends TestCase
{
    private $securityMock;
    private $voter;

    protected function setUp(): void
    {
        $this->securityMock = $this->createMock(Security::class);
        $this->voter = new ClientVoter($this->securityMock);
    }

    public function testVoteWhenAdmin()
    {
        $token = $this->createMock(TokenInterface::class);
        $user = $this->createMock(UserInterface::class);
        $token->method('getUser')->willReturn($user);

        $this->securityMock->method('isGranted')->with('ROLE_ADMIN')->willReturn(true);

        $client = new Client();

        $this->assertSame(Voter::ACCESS_GRANTED, $this->voter->vote($token, $client, [ClientVoter::VIEW]));
        $this->assertSame(Voter::ACCESS_GRANTED, $this->voter->vote($token, $client, [ClientVoter::EDIT]));
    }

    public function testVoteWhenManager()
    {
        $token = $this->createMock(TokenInterface::class);
        $user = $this->createMock(UserInterface::class);
        $token->method('getUser')->willReturn($user);

        $this->securityMock->method('isGranted')->willReturnMap([
            ['ROLE_ADMIN', null, false],
            ['ROLE_MANAGER', null, true],
        ]);

        $client = new Client();

        $this->assertSame(Voter::ACCESS_GRANTED, $this->voter->vote($token, $client, [ClientVoter::EDIT]));
        $this->assertSame(Voter::ACCESS_GRANTED, $this->voter->vote($token, $client, [ClientVoter::VIEW]));
    }

    public function testVoteWhenRegularUser()
    {
        $token = $this->createMock(TokenInterface::class);
        $user = $this->createMock(UserInterface::class);
        $token->method('getUser')->willReturn($user);

        $this->securityMock->method('isGranted')->willReturn(false);

        $client = new Client();

        $this->assertSame(Voter::ACCESS_DENIED, $this->voter->vote($token, $client, [ClientVoter::VIEW]));
        $this->assertSame(Voter::ACCESS_DENIED, $this->voter->vote($token, $client, [ClientVoter::EDIT]));
    }

    public function testAbstainWhenUnsupported()
    {
        $token = $this->createMock(TokenInterface::class);
        $client = new Client();

        // Abstain if the attribute is not supported
        $this->assertSame(Voter::ACCESS_ABSTAIN, $this->voter->vote($token, $client, ['UNSUPPORTED_ACTION']));

        // Abstain if the subject is not a Client
        $this->assertSame(Voter::ACCESS_ABSTAIN, $this->voter->vote($token, new \stdClass(), [ClientVoter::VIEW]));
    }
}
