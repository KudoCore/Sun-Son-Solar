<?php

namespace App\Libraries;

/** Checks browser input before it reaches the database. */
class RegistrationValidator
{
    public function validate(array $input): array
    {
        $fields = [
            'first_name', 'last_name', 'middle_name', 'department', 'email',
            'phone', 'phone_type', 'address', 'username', 'password', 'password_confirmation',
            'consent', 'website', 'birthday', 'gender',
        ];
        $data = [];

        foreach ($fields as $field) {
            $value = $input[$field] ?? '';
            if (!is_string($value)) {
                return ['data' => [], 'error' => 'Please enter text values in the form.'];
            }
            $data[$field] = str_starts_with($field, 'password') ? $value : trim($value);
        }

        if ($data['website'] !== '') {
            return ['data' => [], 'error' => 'Unable to submit this registration.'];
        }
        if ($data['consent'] !== 'on') {
            return ['data' => [], 'error' => 'Please confirm the privacy notice before registering.'];
        }

        $data['username'] = strtolower($data['username']);
        $data['email'] = strtolower($data['email']);
        $validation = service('validation');
        $validation->reset();
        $validation->setRules([
            'first_name' => ['label' => 'First name', 'rules' => 'required|max_length[100]'],
            'last_name' => ['label' => 'Last name', 'rules' => 'required|max_length[100]'],
            'middle_name' => ['label' => 'Middle name', 'rules' => 'permit_empty|max_length[100]'],
            'department' => ['label' => 'Department', 'rules' => 'required|max_length[100]'],
            'email' => ['label' => 'Email', 'rules' => 'required|valid_email|max_length[254]'],
            'phone_type' => ['label' => 'Phone type', 'rules' => 'required|in_list[company,personal]'],
            'phone' => ['label' => 'Phone', 'rules' => 'permit_empty|max_length[30]'],
            'address' => ['label' => 'Address', 'rules' => 'permit_empty|max_length[300]'],
            'username' => ['label' => 'Username', 'rules' => 'required|min_length[3]|max_length[80]|regex_match[/\A[a-zA-Z0-9_.-]+\z/]'],
            'password' => ['label' => 'Password', 'rules' => 'required|min_length[12]'],
            'password_confirmation' => ['label' => 'Password confirmation', 'rules' => 'required|matches[password]'],
        ]);

        if (!$validation->run($data)) {
            $errors = $validation->getErrors();
            return ['data' => [], 'error' => reset($errors)];
        }

        // bcrypt accepts at most 72 bytes; prevent silent password truncation.
        if (strlen($data['password']) > 72 || str_contains($data['password'], "\0")) {
            return ['data' => [], 'error' => 'Use a password of at most 72 bytes with no null characters.'];
        }

        if (!config('Company')->collectHrDetails) {
            $data['birthday'] = '';
            $data['gender'] = '';
        }
        if ($data['birthday'] !== '') {
            $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $data['birthday']);
            if (!$date || $date->format('Y-m-d') !== $data['birthday'] || (int) $date->format('Y') < 1000 || $date > new \DateTimeImmutable('today')) {
                return ['data' => [], 'error' => 'Enter a valid birthday that is not in the future.'];
            }
        }
        if (!in_array($data['gender'], ['', 'Woman', 'Man', 'Non-binary', 'Self-described'], true)) {
            return ['data' => [], 'error' => 'Choose one of the available gender options.'];
        }

        return ['data' => $data, 'error' => null];
    }
}
