<?php

class AuthService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function attemptLogin(string $email, string $password): ?array
    {
        $user = $this->userRepository->findActiveByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return null;
        }

        return $user;
    }
}
