<?php

namespace App\Controllers;

use App\Libraries\RequestLimiter;
use App\Libraries\RegistrationValidator;
use App\Models\UserModel;
use App\Models\DepartmentModel;
use CodeIgniter\HTTP\ResponseInterface;

/** Validates registration and saves a pending account to local MySQL. */
class RegistrationApi extends FormApiController
{
    public function config(): ResponseInterface
    {
        $company = config('Company');

        try {
            $departments = (new DepartmentModel())->choices();
        } catch (\Throwable $exception) {
            return $this->reply(503, ['error' => 'Department choices are unavailable. Please ask IT to apply the database update.']);
        }

        return $this->reply(200, [
            'base_url' => rtrim(config('App')->baseURL, '/'),
            'address' => $company->address,
            'phone' => $company->phone,
            'maps_url' => $company->mapsUrl,
            'response_time' => $company->responseTime,
            'hr_details_enabled' => $company->collectHrDetails,
            'ga_id' => '',
            'departments' => $departments,
        ]);
    }

    public function register(): ResponseInterface
    {
        $error = $this->checkFormRequest();
        if ($error !== null) {
            return $error;
        }
        $data = $this->request->getJSON(true);
        $validation = (new RegistrationValidator())->validate((array) $data);
        if ($validation['error'] !== null) {
            return $this->reply(422, ['error' => $validation['error']]);
        }
        $form = $validation['data'];

        try {
            if (!(new RequestLimiter())->allow('register:' . $this->request->getIPAddress(), 10, 600)) {
                return $this->reply(429, ['error' => 'Too many registration attempts. Please try again in 10 minutes.']);
            }

            $department = (new DepartmentModel())->where('name', $form['department'])->first();
            if (!$department) {
                return $this->reply(422, ['error' => 'Choose one of the available departments.']);
            }

            $users = new UserModel();
            if ($users->accountExists($form['username'], $form['email'])) {
                return $this->reply(409, ['error' => 'That username or email is already registered.']);
            }

            // Build this list explicitly: visitors cannot choose admin roles or approval.
            $timestamp = gmdate('Y-m-d H:i:s');
            $userId = $users->insert([
                'first_name' => $form['first_name'],
                'last_name' => $form['last_name'],
                'middle_name' => $form['middle_name'] ?: null,
                'department' => $department['name'],
                'department_id' => $department['id'],
                'phone_type' => $form['phone_type'],
                'email' => $form['email'],
                'phone' => $form['phone'] ?: null,
                'address' => $form['address'] ?: null,
                'birthday' => $form['birthday'] ?: null,
                'gender' => $form['gender'] ?: null,
                'username' => $form['username'],
                'password_hash' => password_hash($form['password'], PASSWORD_BCRYPT, ['cost' => 12]),
                'role' => 'employee',
                'approved' => 0,
                'consent_at' => $timestamp,
                'created_at' => $timestamp,
            ]);

            if ($userId === false) {
                // Unique indexes also protect against simultaneous duplicate requests.
                if ((int) db_connect()->error()['code'] === 1062) {
                    return $this->reply(409, ['error' => 'That username or email is already registered.']);
                }
                throw new \RuntimeException('Account insert failed.');
            }

            return $this->reply(201, ['message' => 'Registration saved locally for IT review.']);
        } catch (\Throwable $exception) {
            // Never return SQL errors, credentials or password data to the browser.
            return $this->reply(503, ['error' => 'Registration is unavailable. Please ask the administrator to check the local database.']);
        }
    }
}
