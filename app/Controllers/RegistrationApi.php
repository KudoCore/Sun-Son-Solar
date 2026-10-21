<?php

namespace App\Controllers;

use App\Libraries\RequestLimiter;
use App\Libraries\RegistrationValidator;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

/** Validates public customer registration and saves an active customer account. */
class RegistrationApi extends FormApiController
{
    public function config(): ResponseInterface
    {
        $company = config('Company');

        return $this->reply(200, [
            'base_url' => rtrim(config('App')->baseURL, '/'),
            'address' => $company->address,
            'phone' => $company->phone,
            'maps_url' => $company->mapsUrl,
            'response_time' => $company->responseTime,
            'hr_details_enabled' => $company->collectHrDetails,
            'ga_id' => '',
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
                'department' => null,
                'department_id' => null,
                'phone_type' => 'personal',
                'email' => $form['email'],
                'phone' => $form['phone'] ?: null,
                'address' => $form['address'] ?: null,
                'birthday' => $form['birthday'] ?: null,
                'gender' => $form['gender'] ?: null,
                'username' => $form['username'],
                'password_hash' => password_hash($form['password'], PASSWORD_BCRYPT, ['cost' => 12]),
                'role' => 'customer',
                'approved' => 1,
                'is_active' => 1,
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

            return $this->reply(201, ['message' => 'Your customer account is ready. You can now sign in.']);
        } catch (\Throwable $exception) {
            // Never return SQL errors, credentials or password data to the browser.
            return $this->reply(503, ['error' => 'Registration is unavailable. Please ask the administrator to check the local database.']);
        }
    }
}
