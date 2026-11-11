<?php
declare(strict_types=1);
namespace Course\Repository;

use Course\Domain\Item;
use Course\Domain\ItemData;

final class PdoItemRepository implements ItemRepository
{
    public function __construct(private \PDO $pdo) {}
    private function item(array $row): Item
    {
        $ownerId = $row['owner_id'] === null ? null : (int) $row['owner_id'];
        return new Item((int) $row['id'], $row['title'], $row['category'], (int) $row['quantity'],
            (int) $row['price_minor'], $ownerId);
    }
    // Готовый образец: запрос без внешнего ввода, поэтому без параметров.
    public function all(): array
    {
        $sql = 'SELECT id, title, category, quantity, price_minor, owner_id FROM items ORDER BY id';
        return array_map($this->item(...), $this->pdo->query($sql)->fetchAll());
    }
    public function find(int $id): ?Item
    {
        // TODO P04-SQL: Найти запись по id подготовленным запросом; null, если строки нет.
        //throw new \RuntimeException('TODO P04-SQL');
        $sql = 'SELECT id, title, category, quantity, price_minor, owner_id FROM items WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }

        return $this->item($row);
    }
    public function matching(string $query): array
    {
        // TODO P04-SQL: Найти записи, заголовок которых содержит текст запроса; % и _ ищутся буквально.
        // throw new \RuntimeException('TODO P04-SQL');
        
        $sql = 'SELECT id, title, category, quantity, price_minor, owner_id FROM items WHERE title LIKE ? ORDER BY id';
        $stmt = $this->pdo->prepare($sql);

        // Экранируем %, _ и сам обратный слэш \
        $escaped = addcslashes($query, '%_\\');
        $stmt->execute(['%' . $escaped . '%']);

        return array_map($this->item(...), $stmt->fetchAll());
    }
    public function create(ItemData $data, int $ownerId): Item
    {
        // TODO P04-SQL: Добавить строку подготовленным INSERT и вернуть созданную запись с новым id.
        //throw new \RuntimeException('TODO P04-SQL');
        
        $sql = 'INSERT INTO items (title, category, quantity, price_minor, owner_id) VALUES (?, ?, ?, ?, ?)';
        $stmt = $this->pdo->prepare($sql);

        $priceMinor = $data->priceMinor ?? $data->price_minor;

        $stmt->execute([
            $data->title,
            $data->category,
            $data->quantity,
            $priceMinor,
            $ownerId,
        ]);

        $id = (int) $this->pdo->lastInsertId();

        return new Item($id, $data->title, $data->category, $data->quantity, $priceMinor, $ownerId);
    }
}
