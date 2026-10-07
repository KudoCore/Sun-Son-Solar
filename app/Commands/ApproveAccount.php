<?php

namespace App\Commands;

use App\Models\UserModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/** Local administrator command. This command has no public web route. */
class ApproveAccount extends BaseCommand
{
    protected $group = 'Accounts';
    protected $name = 'accounts:approve';
    protected $description = 'Approve one local employee account after verifying their identity.';
    protected $usage = 'accounts:approve <username>';
    protected $arguments = ['username' => 'The employee username to approve.'];

    public function run(array $params)
    {
        $username = strtolower(trim($params[0] ?? ''));
        if (!preg_match('/\A[a-z0-9_.-]{3,80}\z/', $username)) {
            CLI::error('Provide a valid username: php spark accounts:approve username');
            return EXIT_ERROR;
        }
        try {
            $users = new UserModel();
            $user = $users->where('username', $username)->first();
            if (!$user) {
                CLI::error('No account found for that username.');
                return EXIT_ERROR;
            }
            if (!$users->update($user['id'], ['approved' => 1])) {
                CLI::error('The account could not be approved.');
                return EXIT_ERROR;
            }
            CLI::write('Approved ' . $username . '. They can now sign in locally.', 'green');
            return EXIT_SUCCESS;
        } catch (\Throwable $exception) {
            CLI::error('Cannot connect to the local account database. Check MySQL and .env.');
            return EXIT_ERROR;
        }
    }
}
