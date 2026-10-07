<?php

namespace App\Libraries;

/** Database-backed attempt limits shared by registration and login. */
class RequestLimiter
{
    public function allow(string $key, int $limit, int $windowSeconds): bool
    {
        $database = db_connect();
        $now = time();
        $expiresAt = $now + $windowSeconds;
        $clientHash = hash('sha256', $key);

        $database->table('registration_limits')->where('expires_at <', $now)->delete();
        $database->transBegin();

        try {
            // The unique key and transaction serialize simultaneous attempts.
            $sql = 'INSERT INTO registration_limits (client_hash, attempts, expires_at)
                    VALUES (?, 1, ?)
                    ON DUPLICATE KEY UPDATE
                    attempts = IF(expires_at <= ?, 1, attempts + 1),
                    expires_at = IF(expires_at <= ?, ?, expires_at)';
            $result = $database->query($sql, [$clientHash, $expiresAt, $now, $now, $expiresAt]);
            if ($result === false) {
                throw new \RuntimeException('Registration limit unavailable.');
            }

            $row = $database->table('registration_limits')
                ->where('client_hash', $clientHash)->get()->getRowArray();
            $database->transCommit();

            return $row !== null && (int) $row['attempts'] <= $limit;
        } catch (\Throwable $exception) {
            $database->transRollback();
            throw $exception;
        }
    }
}
