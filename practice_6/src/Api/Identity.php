<?php
declare(strict_types=1);
namespace Course\Api;
use Course\Accounts\{User, UserRepository};
use Course\Http\{Request, HttpError};

final class Identity
{
    public function __construct(private UserRepository $users, private Tokens $tokens) {}

    public function require(Request $request, bool $admin = false): User
    {
        $header = $request->header('Authorization');
        if ($header === null || !preg_match('/^Bearer ([^\s]+)$/i', trim($header), $m)) {
            throw new HttpError(401, 'unauthorized', 'Требуется аутентификация', [], ['WWW-Authenticate' => 'Bearer']);
        }
        $token = $m[1];

        $userId = $this->tokens->subject($token);
        if ($userId === null) {
            throw $this->invalidToken();
        }

        $user = $this->users->find($userId);
        if ($user === null) {
            throw $this->invalidToken();
        }

        if ($admin && $user->role !== 'admin') {
            throw new HttpError(403, 'forbidden', 'Недостаточно прав');
        }

        return $user;
    }

    private function invalidToken(): HttpError
    {
        return new HttpError(401, 'invalid_token', 'Токен недействителен или просрочен', [], [
            'WWW-Authenticate' => 'Bearer error="invalid_token"',
        ]);
    }
}
