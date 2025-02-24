<?php

class LockManager
{
    public function __construct(private readonly PDO $db) {}

    /**
     * Выполняет операцию $callback под эксклюзивной блокировкой таблицы $tableName с условием $where
     * @param string $tableName
     * @param array $where
     * @param callable $callback
     * @return mixed
     * @throws Throwable
     */
    public function executeWithLock(string $tableName, array $where, callable $callback): mixed
    {
        $this->db->beginTransaction();

        try {
            $whereParts = [];
            $params = [];
            foreach ($where as $col => $val) {
                $whereParts[] = "$col = :$col";
                $params[":$col"] = $val;
            }

            $whereClause = implode(' AND ', $whereParts);

            $sql = "SELECT * FROM {$tableName} WHERE {$whereClause} FOR UPDATE";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            // Для простоты считаем, что блокируем только одну запись
            $lockedData = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($lockedData === false) {
                $this->db->rollBack();
                throw new RuntimeException('Record not found in table: ' . $tableName);
            }

            $result = $callback($lockedData);

            $this->db->commit();

            return $result;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
