<?php
declare(strict_types=1);
namespace Course\Api;
use Course\Accounts\UserRepository;
use Course\Http\{Request, Response, HttpError};
final class Login
{
    public function __construct(private UserRepository $users, private Tokens $tokens) {}
    public function __invoke(Request $request): Response
    {
        $json_object = Json::object($request);

        $errors = [];

        foreach (array_keys($json_object) as $key) {
            if ($key !== 'email' && $key !== 'password') {
                $errors[(string) $key] = 'Недопустимое поле';
            }
        }

        $email = $json_object['email'] ?? null;
        if (!is_string($email)) {
            $errors['email'] = 'Обязательное строковое поле';
        } elseif (strlen($email) > 254) {
            $errors['email'] = 'Не более 254 байтов';
        }        

        $password = $json_object['password'] ?? null;
        if (!is_string($password)) {
            $errors['password'] = 'Обязательное строковое поле';
        } elseif (strlen($password) > 72) {
            $errors['password'] = 'Не более 72 байтов';
        }

        if ($errors !== []) {
            throw new HttpError(422, 'validation_failed', 'Ошибка валидации', $errors);
        }       

        
        $email = strtolower(trim($email));

        $user = $this->users->byEmail($email);
        if ($user === null || !password_verify($password, $user->passwordHash)) {
            throw new HttpError(401, 'invalid_credentials', 'Неверный email или пароль');
        }        

        
        return Json::response([
            'access_token' => $this->tokens->issue($user->id),
            'token_type'   => 'Bearer',
            'expires_in'   => 900,
            'user'         => [
                'id'   => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ],
        ]);
    }
}
