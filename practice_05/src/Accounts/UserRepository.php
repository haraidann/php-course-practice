<?php
declare(strict_types=1);
namespace Course\Accounts;

use Course\Http\HttpError;

final class UserRepository
{
    public function __construct(private \PDO $pdo) {}
    private function user(array|false $row): ?User
    {
        return $row === false ? null : new User((int) $row['id'], $row['name'],
            $row['email'], $row['password_hash'], $row['role']);
    }


    
    public function find(int $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT id, name, email, password_hash, role FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $this->user($stmt->fetch());
    }

    public function byEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        return $this->user($stmt->fetch());
    }

    public function create(string $name, string $email, string $hash): void
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :hash)');
            $stmt->execute([':name' => $name, ':email' => $email, ':hash' => $hash]);
        } catch (\PDOException $error) {
            if ($error->errorInfo[1] === 1062) {
                throw new HttpError(422, 'email_taken', 'Пользователь с таким email уже существует');
            }
            throw $error;
        }
    }
}
