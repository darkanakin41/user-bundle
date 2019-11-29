<?php

namespace Darkanakin41\UserBundle\Security;


use Darkanakin41\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;

class UsernameAndEmailAuthenticator implements SimpleFormAuthenticatorInterface
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     * @return UsernamePasswordToken
     * @throws \Exception
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {
            $user = $userProvider->loadUserByUsername($token->getUsername());
        } catch (UsernameNotFoundException $exception) {
            if (!method_exists($userProvider, "loadUserByEmail")) {
                throw new \Exception("error.no_account_found");
            }
            try {
                $user = $userProvider->loadUserByEmail($token->getUsername());
            } catch (UsernameNotFoundException $exception) {
                throw new \Exception("error.no_account_found");
            }
        }

        $currentUser = $token->getUser();

        if ($currentUser instanceof UserInterface) {
            if ($currentUser->getPassword() !== $user->getPassword()) {
                throw new CustomUserMessageAuthenticationException('error.wrong_password');
            }
        } else {
            if(!$this->encoder->isPasswordValid($user, $token->getCredentials())){
                throw new CustomUserMessageAuthenticationException('error.wrong_password');
            }
        }

        if (is_null($user->getDateValidation())) {
            throw new CustomUserMessageAuthenticationException("error.account_not_validated");
        }

        if (!$user->getEnable()) {
            throw new CustomUserMessageAuthenticationException("error.account_not_enabled");
        }


        return new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            $providerKey,
            $user->getRoles()
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}
