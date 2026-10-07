<?php

namespace App\Models;

use CodeIgniter\Model;

/** Stores local accounts. Passwords must already be hashed before insert(). */
class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'first_name', 'last_name', 'middle_name', 'birthday', 'gender',
        'email', 'phone', 'address', 'username', 'password_hash',
        'department', 'department_id', 'phone_type', 'role', 'approved', 'consent_at', 'created_at',
    ];

    public function accountExists(string $username, string $email): bool
    {
        return $this->where('username', $username)
            ->orWhere('email', $email)
            ->countAllResults() > 0;
    }
}
