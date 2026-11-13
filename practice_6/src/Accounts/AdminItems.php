<?php
declare(strict_types=1);
namespace Course\Accounts;

use Course\Domain\ItemData;

/** Дополнение к репозиторию № 4; его существующие методы сохраняются. */
final class AdminItems
{
    public function __construct(private \PDO $pdo) {}
    public function update(int $id, ItemData $data): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE items
                SET title = ?, category = ?, quantity = ?, price_minor = ?
              WHERE id = ?'
        );
        $stmt->execute([
            $data->title,
            $data->category,
            $data->quantity,
            $data->priceMinor,
            $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM items WHERE id = ?');
        $stmt->execute([$id]);
    }
}
