<?php
declare(strict_types=1);
namespace Course\Accounts;

use Course\Http\HttpError;

final class Registration
{
    /** @return array{name:string,email:string,password:string} */
    public static function validate(array $input): array
    {
        $errors = [];
        
        // name
        $name = $input['name'] ?? null;
        if (!is_string($name)) {
            $errors['name'] = 'Имя обязательно';
        } else {
            $name = trim($name);
            $len = mb_strlen($name, 'UTF-8');
            if ($len < 2 || $len > 80) {
                $errors['name'] = 'От 2 до 80 символов';
            }
        }
        
        // email
        $email = $input['email'] ?? null;
        if (!is_string($email)) {
            $errors['email'] = 'Email обязателен';
        } else {
            $email = strtolower(trim($email));
            if (strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Некорректный email';
            }
        }
        
        // password
        $password = $input['password'] ?? null;
        if (!is_string($password)) {
            $errors['password'] = 'Пароль обязателен';
        } else {
            $len = strlen($password);
            if ($len < 8 || $len > 72 || str_contains($password, "\0")) {
                $errors['password'] = 'От 8 до 72 байт, без нулевого байта';
            }
        }
        
        // confirmation
        $confirmation = $input['confirmation'] ?? null;
        if (!is_string($confirmation) || $confirmation !== $password) {
            $errors['confirmation'] = 'Пароли не совпадают';
        }
        
        // Прочие поля — клиент не назначает role
        if (isset($input['role'])) {
            $errors['role'] = 'Роль нельзя задать';
        }
        
        if ($errors !== []) {
            throw new HttpError(422, 'validation_failed', 'Проверьте поля формы', $errors);
        }
        
        return ['name' => $name, 'email' => $email, 'password' => $password];
    }
}
