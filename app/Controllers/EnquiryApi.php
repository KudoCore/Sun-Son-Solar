<?php

namespace App\Controllers;

use App\Libraries\RequestLimiter;
use CodeIgniter\HTTP\ResponseInterface;

/** Saves public enquiries to local MySQL without sending data to another service. */
class EnquiryApi extends FormApiController
{
    public function submit(): ResponseInterface
    {
        $error = $this->checkFormRequest();
        if ($error !== null) {
            return $error;
        }

        $data = $this->request->getJSON(true);
        $form = [];
        foreach (['name', 'email', 'phone', 'interest', 'message', 'consent', 'website'] as $field) {
            $value = $data[$field] ?? '';
            if (!is_string($value)) {
                return $this->reply(422, ['error' => 'Check the form fields and try again.']);
            }
            $form[$field] = trim($value);
        }
        $interests = [
            'Complete solar system', 'Solar panels', 'Inverters', 'Batteries',
            'Racking & mounting', 'Wires & connections', 'Consultation', 'Designing',
            'Permitting', 'Installation', 'Maintenance', 'Repair', 'Monitoring',
        ];
        if ($form['name'] === '' || mb_strlen($form['name']) > 120
            || !filter_var($form['email'], FILTER_VALIDATE_EMAIL) || strlen($form['email']) > 254
            || mb_strlen($form['phone']) > 30 || !in_array($form['interest'], $interests, true)
            || mb_strlen($form['message']) < 10 || mb_strlen($form['message']) > 3000
            || $form['consent'] !== 'on' || $form['website'] !== '') {
            return $this->reply(422, ['error' => 'Enter a valid name, email, product or service, and project message. Accept the privacy notice before sending.']);
        }

        try {
            if (!(new RequestLimiter())->allow('enquiry:' . $this->request->getIPAddress(), 5, 600)) {
                return $this->reply(429, ['error' => 'Too many enquiries. Please try again in 10 minutes.']);
            }
            $reference = 'SS-' . strtoupper(bin2hex(random_bytes(8)));
            $now = gmdate('Y-m-d H:i:s');
            $saved = db_connect()->table('enquiries')->insert([
                'reference' => $reference,
                'name' => $form['name'],
                'email' => $form['email'],
                'phone' => $form['phone'] ?: null,
                'interest' => $form['interest'],
                'message' => $form['message'],
                'consent_at' => $now,
                'created_at' => $now,
            ]);
            if (!$saved) {
                throw new \RuntimeException('Could not save enquiry.');
            }
            session()->set('enquiry_reference', $reference);
            return $this->reply(201, ['message' => 'Your enquiry has been saved.', 'reference' => $reference]);
        } catch (\Throwable $exception) {
            return $this->reply(503, ['error' => 'Your enquiry could not be saved. Please ask the administrator to check the local enquiries database table, then try again.']);
        }
    }
}
