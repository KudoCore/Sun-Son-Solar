<?php

namespace App\Models;

use CodeIgniter\Model;

/** The department choices confirmed during the follow-up interview. */
class DepartmentModel extends Model
{
    protected $table = 'departments';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['name'];

    public function choices(): array
    {
        return $this->orderBy('id')->findAll();
    }
}
