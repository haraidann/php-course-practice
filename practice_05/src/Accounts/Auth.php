<?php
declare(strict_types=1);
namespace Course\Accounts;

use Course\Http\HttpError;
use Course\Http\Response;
use Course\Security\AccountSession;

final class Auth
{
    public function __construct(private UserRepository $users) {}

    public function current(): ?User
    {
        $id = $_SESSION['user_id'] ?? null;
        return is_int($id) ? $this->users->find($id) : null;
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->users->byEmail($email);
        if ($user === null || !password_verify($password, $user->passwordHash)) {
            return false;
        }
        AccountSession::renew();
        $_SESSION['user_id'] = $user->id;
        return true;
    }

    public function logout(): void
    {
        AccountSession::finish();
    }

    public function guard(bool $admin = false): User|Response
    {
        $user = $this->current();
        if ($user === null) {
            return Response::redirect('/login');
        }
        if ($admin && $user->role !== 'admin') {
            return new Response(403, 'Доступ запрещён', ['Content-Type' => 'text/plain; charset=UTF-8']);
        }
        return $user;
    }
}
