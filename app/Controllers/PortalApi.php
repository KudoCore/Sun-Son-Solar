<?php

namespace App\Controllers;

use App\Libraries\LocalAuth;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

/** Role-checked endpoints for customer, dispatcher, admin, HR, and technician portals. */
class PortalApi extends FormApiController
{
    private const DEPARTMENTS = ['Administration', 'IT', 'Dispatch', 'Accounting', 'HR', 'Marketing', 'Sales', 'Customer Service'];
    private const STAFF_ROLES = ['employee', 'technician', 'dispatcher', 'admin', 'hr'];

    public function data(): ResponseInterface
    {
        $user = $this->signedIn();
        if ($user instanceof ResponseInterface) return $user;
        try {
            $db = db_connect();
            $role = $user['role'];
            $manager = in_array($role, ['admin', 'hr', 'dispatcher'], true);
            $customer = $role === 'customer';
            $bookings = $db->table('service_bookings b')
                ->select('b.id,b.customer_id,b.category_id,b.technician_id,b.requested_at,b.scheduled_start,b.scheduled_end,b.service_address,b.customer_notes,b.status,b.staff_notes,b.created_at,c.name AS service_name,cu.first_name AS customer_first,cu.last_name AS customer_last,t.first_name AS technician_first,t.last_name AS technician_last')
                ->join('service_categories c', 'c.id=b.category_id')
                ->join('users cu', 'cu.id=b.customer_id')
                ->join('users t', 't.id=b.technician_id', 'left')
                ->orderBy('COALESCE(b.scheduled_start,b.requested_at)', 'DESC', false);
            if ($customer) $bookings->where('b.customer_id', $user['id']);
            elseif ($role === 'technician') $bookings->where('b.technician_id', $user['id']);
            elseif (!in_array($role, ['admin','dispatcher'], true)) $bookings->where('b.id', 0);
            $bookingRows = $bookings->get()->getResultArray();
            if ($role === 'technician') {
                foreach ($bookingRows as &$bookingRow) unset($bookingRow['customer_notes'], $bookingRow['staff_notes']);
                unset($bookingRow);
            }

            $notifications = $db->table('notifications')->where('user_id', $user['id'])->orWhere('user_id', null)
                ->orderBy('created_at', 'DESC')->limit(30)->get()->getResultArray();
            $ticketsQuery = $db->table('support_tickets st')->select('st.*')->orderBy('st.updated_at', 'DESC')->limit(50);
            if (!in_array($role, ['admin','hr','dispatcher'], true)) $ticketsQuery->where('st.user_id', $user['id']);
            $tickets = $ticketsQuery->get()->getResultArray();
            $paymentsQuery = $db->table('service_payments p')->select('p.*,b.service_address,c.name AS service_name')
                ->join('service_bookings b', 'b.id=p.booking_id')->join('service_categories c', 'c.id=b.category_id')
                ->orderBy('p.created_at', 'DESC')->limit(50);
            if ($role !== 'admin') $paymentsQuery->where('p.customer_id', $customer ? $user['id'] : 0);
            $payments = $paymentsQuery->get()->getResultArray();
            $categories = $db->table('service_categories')->where('is_active', 1)->orderBy('name')->get()->getResultArray();
            $payload = [
                'user' => $user,
                'bookings' => $bookingRows,
                'notifications' => $notifications,
                'tickets' => $tickets,
                'payments' => $payments,
                'categories' => $categories,
                'preferences' => $db->table('user_preferences')->where('user_id', $user['id'])->get()->getRowArray() ?? ['promotions' => 1, 'schedule_updates' => 1],
                'announcements' => [],
            ];
            if ($customer) $payload['testimonial'] = $db->table('customer_testimonials')->where('user_id', $user['id'])->orderBy('created_at', 'DESC')->get()->getRowArray();
            if ($role !== 'customer') {
                $announcements = $db->table('announcements')->where('is_active', 1)->groupStart()->where('audience', 'all_staff');
                $audience = match ($role) {
                    'technician' => 'technicians',
                    'dispatcher' => 'dispatchers',
                    'admin', 'hr' => 'managers',
                    default => null,
                };
                if ($audience !== null) $announcements->orWhere('audience', $audience);
                $payload['announcements'] = $announcements->groupEnd()->orderBy('created_at', 'DESC')->limit(30)->get()->getResultArray();
            }
            if (in_array($role, ['admin','dispatcher'], true)) {
                $payload['technicians'] = $db->table('users')->select('id,first_name,last_name')->where(['role' => 'technician', 'approved' => 1, 'is_active' => 1])->orderBy('first_name')->get()->getResultArray();
            }
            if (in_array($role, ['admin', 'hr'], true)) {
                $payload['users'] = $db->table('users')->select('id,first_name,middle_name,last_name,birthday,gender,email,phone,address,username,department,role,approved,is_active,created_at')->orderBy('created_at', 'DESC')->limit(300)->get()->getResultArray();
                $payload['departments'] = self::DEPARTMENTS;
            }
            if ($role === 'admin') {
                $payload['all_categories'] = $db->table('service_categories')->orderBy('name')->get()->getResultArray();
                $payload['settings'] = array_column($db->table('app_settings')->get()->getResultArray(), 'setting_value', 'setting_key');
                $payload['analytics'] = $this->analytics($db);
                $payload['payments'] = $db->table('service_payments p')->select('p.*,b.customer_id,b.id AS service_booking_id,c.name AS service_name')
                    ->join('service_bookings b', 'b.id=p.booking_id')->join('service_categories c', 'c.id=b.category_id')->orderBy('p.created_at', 'DESC')->limit(50)->get()->getResultArray();
                $payload['reports'] = $db->table('technician_reports r')->select('r.*,u.first_name,u.last_name,b.service_address')
                    ->join('users u', 'u.id=r.technician_id')->join('service_bookings b', 'b.id=r.booking_id')->orderBy('r.created_at', 'DESC')->limit(50)->get()->getResultArray();
                $payload['testimonials'] = $db->table('customer_testimonials')->orderBy('created_at', 'DESC')->limit(100)->get()->getResultArray();
            }
            return $this->reply(200, $payload);
        } catch (\Throwable $exception) {
            return $this->reply(503, ['error' => 'Portal data is unavailable. Ask an administrator to check the database update.']);
        }
    }

    public function booking(): ResponseInterface
    {
        $error = $this->checkFormRequest(); if ($error) return $error;
        $user = $this->signedIn(); if ($user instanceof ResponseInterface) return $user;
        if ($user['role'] !== 'customer') return $this->reply(403, ['error' => 'Only customers may request service.']);
        $data = $this->body(); if ($data instanceof ResponseInterface) return $data;
        $categoryId = filter_var($data['category_id'] ?? null, FILTER_VALIDATE_INT);
        $address = trim((string) ($data['service_address'] ?? $user['address'] ?? ''));
        $notes = trim((string) ($data['customer_notes'] ?? ''));
        if (!$categoryId || $address === '' || mb_strlen($address) > 300 || mb_strlen($notes) > 3000) return $this->reply(422, ['error' => 'Choose a service and enter the service address.']);
        try {
            $db = db_connect();
            if (($db->table('app_settings')->where('setting_key', 'booking_enabled')->get()->getRow('setting_value') ?? '1') !== '1') return $this->reply(403, ['error' => 'Online service requests are temporarily paused.']);
            $category = $db->table('service_categories')->where(['id' => $categoryId, 'is_active' => 1])->get()->getRowArray();
            if (!$category) return $this->reply(422, ['error' => 'That service is not available.']);
            $requestedAt = $data['requested_at'] ?? null;
            if ($requestedAt !== null && $requestedAt !== '') {
                $date = \DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', (string) $requestedAt, new \DateTimeZone('Asia/Manila'));
                if (!$date || $date < new \DateTimeImmutable('now')) return $this->reply(422, ['error' => 'Choose a future preferred date and time.']);
                $requestedAt = $date->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s');
            } else $requestedAt = null;
            $db->table('service_bookings')->insert(['customer_id' => $user['id'], 'category_id' => $categoryId, 'requested_at' => $requestedAt, 'service_address' => $address, 'customer_notes' => $notes ?: null, 'status' => 'requested']);
            $id = $db->insertID();
            $this->notifyUser($db, $user['id'], 'Service request received', 'Your ' . $category['name'] . ' request #' . $id . ' is awaiting scheduling.', 'schedule', 'schedule_updates');
            return $this->reply(201, ['message' => 'Service request submitted.', 'id' => $id]);
        } catch (\Throwable $exception) { return $this->reply(503, ['error' => 'Could not save the service request.']); }
    }

    public function profile(): ResponseInterface
    {
        $error = $this->checkFormRequest(); if ($error) return $error;
        $user = $this->signedIn(); if ($user instanceof ResponseInterface) return $user;
        $data = $this->body(); if ($data instanceof ResponseInterface) return $data;
        $fields = ['first_name','middle_name','last_name','birthday','gender','email','phone','address'];
        $values = [];
        foreach ($fields as $field) {
            $value = $data[$field] ?? '';
            if (!is_string($value)) return $this->reply(422, ['error' => 'Invalid profile field.']);
            $values[$field] = trim($value) ?: null;
        }
        if (!$values['first_name'] || !$values['last_name'] || !$values['email'] || !$values['phone'] || !$values['address'] || !filter_var($values['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($values['address']) > 300) return $this->reply(422, ['error' => 'Enter your name, valid email, phone and address.']);
        if (!in_array($values['gender'], ['Woman','Man','Non-binary','Self-described','Prefer not to say',null], true)) return $this->reply(422, ['error' => 'Choose a valid gender option.']);
        if ($values['birthday'] !== null) {
            $birthday = \DateTimeImmutable::createFromFormat('!Y-m-d', $values['birthday']);
            if (!$birthday || $birthday->format('Y-m-d') !== $values['birthday'] || $birthday > new \DateTimeImmutable('today')) return $this->reply(422, ['error' => 'Enter a valid birthdate.']);
        }
        $db = db_connect();
        if ($db->table('users')->where('email', $values['email'])->where('id !=', $user['id'])->countAllResults()) return $this->reply(409, ['error' => 'That email is already in use.']);
        try {
            (new UserModel())->update($user['id'], $values);
            $prefs = ['promotions' => !empty($data['promotions']) ? 1 : 0, 'schedule_updates' => !empty($data['schedule_updates']) ? 1 : 0];
            $db->table('user_preferences')->replace(array_merge(['user_id' => $user['id']], $prefs));
            return $this->reply(200, ['message' => 'Profile updated.']);
        } catch (\Throwable $exception) { return $this->reply(503, ['error' => 'Could not update your profile.']); }
    }

    public function password(): ResponseInterface
    {
        $error = $this->checkFormRequest(); if ($error) return $error;
        $user = $this->signedIn(); if ($user instanceof ResponseInterface) return $user;
        $data = $this->body(); if ($data instanceof ResponseInterface) return $data;
        $current = $data['current_password'] ?? ''; $new = $data['new_password'] ?? '';
        $record = (new UserModel())->find($user['id']);
        if (!is_string($current) || !is_string($new) || !password_verify($current, $record['password_hash']) || strlen($new) < 12 || strlen($new) > 72 || str_contains($new, "\0")) return $this->reply(422, ['error' => 'Check your current password and enter a new password of at least 12 characters.']);
        (new UserModel())->update($user['id'], ['password_hash' => password_hash($new, PASSWORD_BCRYPT, ['cost' => 12])]);
        (new LocalAuth())->logout();
        return $this->reply(200, ['message' => 'Password changed. Please sign in again.']);
    }

    public function ticket(): ResponseInterface
    {
        $error = $this->checkFormRequest(); if ($error) return $error;
        $user = $this->signedIn(); if ($user instanceof ResponseInterface) return $user;
        $data = $this->body(); if ($data instanceof ResponseInterface) return $data;
        $subject = trim((string) ($data['subject'] ?? '')); $message = trim((string) ($data['message'] ?? ''));
        if ($subject === '' || mb_strlen($subject) > 160 || $message === '' || mb_strlen($message) > 5000) return $this->reply(422, ['error' => 'Enter a subject and a message of up to 5,000 characters.']);
        db_connect()->table('support_tickets')->insert(['user_id' => $user['id'], 'subject' => $subject, 'message' => $message]);
        return $this->reply(201, ['message' => 'Support ticket submitted.']);
    }

    public function cancelBooking(): ResponseInterface
    {
        $error = $this->checkFormRequest(); if ($error) return $error;
        $user = $this->signedIn(); if ($user instanceof ResponseInterface) return $user;
        if ($user['role'] !== 'customer') return $this->reply(403, ['error' => 'Customer access required.']);
        $data = $this->body(); if ($data instanceof ResponseInterface) return $data;
        $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
        $db = db_connect(); $booking = $id ? $db->table('service_bookings')->where(['id' => $id, 'customer_id' => $user['id']])->get()->getRowArray() : null;
        if (!$booking || !in_array($booking['status'], ['requested','approved','scheduled'], true)) return $this->reply(404, ['error' => 'This request can no longer be cancelled.']);
        $db->table('service_bookings')->where('id', $id)->update(['status' => 'cancelled']);
        return $this->reply(200, ['message' => 'Service request cancelled.']);
    }

    public function payment(): ResponseInterface
    {
        $error = $this->checkFormRequest(); if ($error) return $error;
        $user = $this->signedIn(); if ($user instanceof ResponseInterface) return $user;
        if ($user['role'] !== 'admin') return $this->reply(403, ['error' => 'Admin access required.']);
        $data = $this->body(); if ($data instanceof ResponseInterface) return $data;
        $bookingId = filter_var($data['booking_id'] ?? null, FILTER_VALIDATE_INT);
        $amount = filter_var($data['amount'] ?? null, FILTER_VALIDATE_FLOAT);
        $status = (string) ($data['status'] ?? 'paid'); $reference = trim((string) ($data['reference'] ?? ''));
        if (!$bookingId || $amount === false || $amount <= 0 || !in_array($status, ['pending','paid','refunded'], true) || mb_strlen($reference) > 80) return $this->reply(422, ['error' => 'Enter a valid booking, amount, status and reference.']);
        $db = db_connect(); $booking = $db->table('service_bookings')->where('id', $bookingId)->get()->getRowArray();
        if (!$booking) return $this->reply(404, ['error' => 'Booking not found.']);
        $db->table('service_payments')->insert(['booking_id' => $bookingId, 'customer_id' => $booking['customer_id'], 'amount' => $amount, 'status' => $status, 'reference' => $reference ?: null, 'paid_at' => $status === 'paid' ? gmdate('Y-m-d H:i:s') : null]);
        return $this->reply(201, ['message' => 'Payment record saved.']);
    }

    public function testimonial(): ResponseInterface
    {
        $error = $this->checkFormRequest(); if ($error) return $error;
        $user = $this->signedIn(); if ($user instanceof ResponseInterface) return $user;
        $data = $this->body(); if ($data instanceof ResponseInterface) return $data;
        $db = db_connect();
        if ($user['role'] === 'customer') {
            $quote = trim((string) ($data['quote_text'] ?? ''));
            $name = trim((string) ($data['display_name'] ?? ''));
            if ($quote === '' || mb_strlen($quote) > 1200 || $name === '' || mb_strlen($name) > 120 || empty($data['consent_to_publish'])) return $this->reply(422, ['error' => 'Enter your story, choose a display name, and confirm publication consent.']);
            $db->table('customer_testimonials')->insert(['user_id' => $user['id'], 'quote_text' => $quote, 'display_name' => $name, 'consent_to_publish' => 1]);
            return $this->reply(201, ['message' => 'Your story was sent to the team for review.']);
        }
        if ($user['role'] !== 'admin') return $this->reply(403, ['error' => 'Admin access required.']);
        $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
        $publish = !empty($data['publish']) ? 1 : 0;
        if (!$id || !$db->table('customer_testimonials')->where(['id' => $id, 'consent_to_publish' => 1])->countAllResults()) return $this->reply(404, ['error' => 'Consented customer story not found.']);
        $db->table('customer_testimonials')->where('id', $id)->update(['is_published' => $publish]);
        return $this->reply(200, ['message' => $publish ? 'Customer story published.' : 'Customer story unpublished.']);
    }

    public function employee(): ResponseInterface
    {
        $error = $this->checkFormRequest(); if ($error) return $error;
        $actor = $this->signedIn(); if ($actor instanceof ResponseInterface) return $actor;
        if (!in_array($actor['role'], ['admin','hr'], true)) return $this->reply(403, ['error' => 'Only Admin or HR may manage accounts.']);
        $data = $this->body(); if ($data instanceof ResponseInterface) return $data;
        $action = $data['action'] ?? 'create'; $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
        try {
            $users = new UserModel();
            if ($action === 'edit') {
                if (!$id) return $this->reply(422, ['error' => 'Select an account.']);
                $target = $users->find($id);
                if (!$target) return $this->reply(404, ['error' => 'Account not found.']);
                if ($target['role'] === 'admin' && $actor['role'] !== 'admin') return $this->reply(403, ['error' => 'HR cannot edit an Admin account.']);
                $role = (string) ($data['role'] ?? $target['role']);
                if ($target['role'] === 'customer') $role = 'customer';
                elseif (!in_array($role, self::STAFF_ROLES, true)) return $this->reply(422, ['error' => 'Choose a valid employee role.']);
                if ($actor['role'] !== 'admin' && in_array($role, ['admin','hr'], true) && $role !== $target['role']) return $this->reply(403, ['error' => 'Only Admins may assign Admin or HR roles.']);
                $fields = [];
                foreach (['first_name','middle_name','last_name','birthday','gender','email','phone','address'] as $field) {
                    if (array_key_exists($field, $data)) $fields[$field] = trim((string) $data[$field]) ?: null;
                }
                if (isset($data['email']) && !filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) return $this->reply(422, ['error' => 'Enter a valid email address.']);
                if (isset($data['gender']) && !in_array($fields['gender'], ['Woman','Man','Non-binary','Self-described','Prefer not to say',null], true)) return $this->reply(422, ['error' => 'Choose a valid gender option.']);
                if (!empty($fields['birthday'])) {
                    $birthday = \DateTimeImmutable::createFromFormat('!Y-m-d', $fields['birthday']);
                    if (!$birthday || $birthday->format('Y-m-d') !== $fields['birthday'] || $birthday > new \DateTimeImmutable('today')) return $this->reply(422, ['error' => 'Enter a valid birthdate.']);
                }
                if (isset($data['email']) && db_connect()->table('users')->where('email', $fields['email'])->where('id !=', $id)->countAllResults()) return $this->reply(409, ['error' => 'That email is already in use.']);
                if ($target['role'] !== 'customer') {
                    $department = (string) ($data['department'] ?? $target['department']);
                    if (!in_array($department, self::DEPARTMENTS, true)) return $this->reply(422, ['error' => 'Choose a valid department.']);
                    $dept = db_connect()->table('departments')->where('name', $department)->get()->getRowArray();
                    $fields['department'] = $department; $fields['department_id'] = $dept['id'] ?? null; $fields['role'] = $role;
                }
                if (isset($data['password']) && $data['password'] !== '') {
                    $password = $data['password'];
                    if (!is_string($password) || strlen($password) < 12 || strlen($password) > 72) return $this->reply(422, ['error' => 'Password must contain at least 12 characters.']);
                    $fields['password_hash'] = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                }
                if ($fields) $users->update($id, $fields);
                return $this->reply(200, ['message' => 'Account updated.']);
            }
            if ($action === 'deactivate' || $action === 'activate') {
                if (!$id) return $this->reply(422, ['error' => 'Select an account.']);
                $target = $users->find($id); if (!$target) return $this->reply(404, ['error' => 'Account not found.']);
                if ($target['role'] === 'admin' && $actor['role'] !== 'admin') return $this->reply(403, ['error' => 'HR cannot change an Admin account.']);
                $users->update($id, ['is_active' => $action === 'activate' ? 1 : 0, 'approved' => $action === 'activate' ? 1 : 0]);
                return $this->reply(200, ['message' => $action === 'activate' ? 'Account activated.' : 'Account deactivated.']);
            }
            $role = (string) ($data['role'] ?? 'employee'); $department = (string) ($data['department'] ?? '');
            if (!in_array($department, self::DEPARTMENTS, true) || !in_array($role, self::STAFF_ROLES, true)) return $this->reply(422, ['error' => 'Choose a valid employee department and role.']);
            if ($actor['role'] !== 'admin' && in_array($role, ['admin','hr'], true)) return $this->reply(403, ['error' => 'Only Admins may add Admin or HR accounts.']);
            $fields = [];
            foreach (['first_name','middle_name','last_name','birthday','gender','email','phone','address','username'] as $field) $fields[$field] = trim((string) ($data[$field] ?? ''));
            $birthday = \DateTimeImmutable::createFromFormat('!Y-m-d', $fields['birthday']);
            if ($fields['first_name'] === '' || $fields['last_name'] === '' || $fields['email'] === '' || !filter_var($fields['email'], FILTER_VALIDATE_EMAIL) || $fields['phone'] === '' || $fields['address'] === '' || !$birthday || $birthday->format('Y-m-d') !== $fields['birthday'] || $birthday > new \DateTimeImmutable('today') || !in_array($fields['gender'], ['Woman','Man','Non-binary','Self-described','Prefer not to say'], true) || !preg_match('/\A[a-zA-Z0-9_.-]{3,80}\z/', $fields['username'])) return $this->reply(422, ['error' => 'Complete all required employee details with a valid birthdate and gender.']);
            $duplicate = db_connect()->table('users')->groupStart()->where('username', strtolower($fields['username']))->orWhere('email', strtolower($fields['email']))->groupEnd();
            if ($id) $duplicate->where('id !=', $id);
            if ($duplicate->countAllResults()) return $this->reply(409, ['error' => 'That username or email is already in use.']);
            $dept = db_connect()->table('departments')->where('name', $department)->get()->getRowArray();
            if (!$dept) return $this->reply(422, ['error' => 'Department not found. Apply the stakeholder database update.']);
            $fields['username'] = strtolower($fields['username']); $fields['email'] = strtolower($fields['email']);
            $fields['department'] = $department; $fields['department_id'] = $dept['id']; $fields['phone_type'] = 'personal';
            $fields['role'] = $role; $fields['approved'] = 1; $fields['is_active'] = 1;
            if ($id) {
                unset($fields['username']);
                $users->update($id, $fields);
                return $this->reply(200, ['message' => 'Account updated.']);
            }
            $password = $data['password'] ?? '';
            if (!is_string($password) || strlen($password) < 12 || strlen($password) > 72 || str_contains($password, "\0")) return $this->reply(422, ['error' => 'Set a temporary password of at least 12 characters.']);
            $fields['password_hash'] = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $fields['consent_at'] = gmdate('Y-m-d H:i:s'); $fields['created_at'] = gmdate('Y-m-d H:i:s');
            $users->insert($fields);
            return $this->reply(201, ['message' => 'Employee account created.']);
        } catch (\Throwable $exception) { return $this->reply(503, ['error' => 'Account change could not be saved.']); }
    }

    public function updateBooking(): ResponseInterface
    {
        $error = $this->checkFormRequest(); if ($error) return $error;
        $actor = $this->signedIn(); if ($actor instanceof ResponseInterface) return $actor;
        if (!in_array($actor['role'], ['admin','dispatcher'], true)) return $this->reply(403, ['error' => 'Only Admins and Dispatchers can change schedules.']);
        $data = $this->body(); if ($data instanceof ResponseInterface) return $data;
        $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT); $status = (string) ($data['status'] ?? 'scheduled');
        if (!$id || !in_array($status, ['requested','approved','declined','scheduled','in_progress','completed','cancelled'], true)) return $this->reply(422, ['error' => 'Choose a valid booking and status.']);
        $db = db_connect(); $booking = $db->table('service_bookings')->where('id', $id)->get()->getRowArray();
        if (!$booking) return $this->reply(404, ['error' => 'Booking not found.']);
        $technician = filter_var($data['technician_id'] ?? null, FILTER_VALIDATE_INT) ?: null;
        if ($technician && !$db->table('users')->where(['id' => $technician, 'role' => 'technician', 'approved' => 1, 'is_active' => 1])->countAllResults()) return $this->reply(422, ['error' => 'Select an active technician.']);
        $start = trim((string) ($data['scheduled_start'] ?? '')) ?: null; $end = trim((string) ($data['scheduled_end'] ?? '')) ?: null;
        $localTimezone = new \DateTimeZone('Asia/Manila'); $utcTimezone = new \DateTimeZone('UTC');
        $startDate = $start ? \DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', $start, $localTimezone) : null;
        $endDate = $end ? \DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', $end, $localTimezone) : null;
        if ($status === 'scheduled' && (!$startDate || !$endDate || $startDate >= $endDate)) return $this->reply(422, ['error' => 'Enter a valid schedule start and end.']);
        $staffNotes = trim((string) ($data['staff_notes'] ?? ''));
        $db->table('service_bookings')->where('id', $id)->update(['status' => $status, 'technician_id' => $technician, 'scheduled_start' => $startDate ? $startDate->setTimezone($utcTimezone)->format('Y-m-d H:i:s') : null, 'scheduled_end' => $endDate ? $endDate->setTimezone($utcTimezone)->format('Y-m-d H:i:s') : null, 'staff_notes' => $staffNotes ?: null]);
        $this->notifyUser($db, $booking['customer_id'], 'Service schedule updated', 'Your service request #' . $id . ' is now ' . str_replace('_', ' ', $status) . '.', 'schedule', 'schedule_updates');
        return $this->reply(200, ['message' => 'Booking updated.']);
    }

    public function checkin(): ResponseInterface
    {
        $error = $this->checkFormRequest(); if ($error) return $error;
        $user = $this->signedIn(); if ($user instanceof ResponseInterface) return $user;
        if ($user['role'] !== 'technician') return $this->reply(403, ['error' => 'Technician access required.']);
        $data = $this->body(); if ($data instanceof ResponseInterface) return $data;
        $id = filter_var($data['booking_id'] ?? null, FILTER_VALIDATE_INT);
        $lat = filter_var($data['latitude'] ?? null, FILTER_VALIDATE_FLOAT); $lng = filter_var($data['longitude'] ?? null, FILTER_VALIDATE_FLOAT);
        if (!$id || $lat === false || $lng === false || $lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) return $this->reply(422, ['error' => 'Location is required for check-in.']);
        $db = db_connect(); $booking = $db->table('service_bookings')->where(['id' => $id, 'technician_id' => $user['id']])->get()->getRowArray();
        if (!$booking) return $this->reply(404, ['error' => 'This job is not assigned to your account.']);
        $db->table('technician_checkins')->insert(['booking_id' => $id, 'technician_id' => $user['id'], 'checked_in_at' => gmdate('Y-m-d H:i:s'), 'latitude' => $lat, 'longitude' => $lng]);
        $db->table('service_bookings')->where('id', $id)->update(['status' => 'in_progress']);
        $this->notifyUser($db, $booking['customer_id'], 'Technician arrived', 'Your technician has checked in at the service location.', 'schedule', 'schedule_updates');
        return $this->reply(201, ['message' => 'Check-in recorded with server time and GPS coordinates.']);
    }

    public function report(): ResponseInterface
    {
        $error = $this->checkFormRequest(); if ($error) return $error;
        $user = $this->signedIn(); if ($user instanceof ResponseInterface) return $user;
        if ($user['role'] !== 'technician') return $this->reply(403, ['error' => 'Technician access required.']);
        $data = $this->body(); if ($data instanceof ResponseInterface) return $data;
        $id = filter_var($data['booking_id'] ?? null, FILTER_VALIDATE_INT); $report = trim((string) ($data['report'] ?? ''));
        if (!$id || $report === '' || mb_strlen($report) > 10000) return $this->reply(422, ['error' => 'Enter an issue report.']);
        $db = db_connect(); $booking = $db->table('service_bookings')->where(['id' => $id, 'technician_id' => $user['id']])->get()->getRowArray();
        if (!$booking) return $this->reply(404, ['error' => 'This job is not assigned to your account.']);
        $db->table('technician_reports')->insert(['booking_id' => $id, 'technician_id' => $user['id'], 'report' => $report]);
        foreach ($db->table('users')->select('id')->where('role', 'admin')->where('is_active', 1)->get()->getResultArray() as $admin) $db->table('notifications')->insert(['user_id' => $admin['id'], 'title' => 'Technician issue report', 'message' => 'A report was submitted for service request #' . $id . '.', 'kind' => 'report']);
        return $this->reply(201, ['message' => 'Report sent to the admin dashboard.']);
    }

    public function announcement(): ResponseInterface
    {
        $error = $this->checkFormRequest(); if ($error) return $error;
        $user = $this->signedIn(); if ($user instanceof ResponseInterface) return $user;
        if (!in_array($user['role'], ['admin','hr'], true)) return $this->reply(403, ['error' => 'Only Admin or HR may publish announcements.']);
        $data = $this->body(); if ($data instanceof ResponseInterface) return $data;
        $title = trim((string) ($data['title'] ?? '')); $body = trim((string) ($data['body'] ?? '')); $audience = (string) ($data['audience'] ?? 'all_staff');
        if ($title === '' || mb_strlen($title) > 160 || $body === '' || mb_strlen($body) > 10000 || !in_array($audience, ['all_staff','technicians','dispatchers','managers'], true)) return $this->reply(422, ['error' => 'Enter an announcement and choose a valid audience.']);
        db_connect()->table('announcements')->insert(['title' => $title, 'body' => $body, 'audience' => $audience, 'created_by' => $user['id']]);
        return $this->reply(201, ['message' => 'Announcement published.']);
    }

    public function settings(): ResponseInterface
    {
        $error = $this->checkFormRequest(); if ($error) return $error;
        $user = $this->signedIn(); if ($user instanceof ResponseInterface) return $user;
        if ($user['role'] !== 'admin') return $this->reply(403, ['error' => 'Admin access required.']);
        $data = $this->body(); if ($data instanceof ResponseInterface) return $data;
        $db = db_connect(); $action = $data['action'] ?? '';
        if ($action === 'availability') {
            $db->table('app_settings')->replace(['setting_key' => 'booking_enabled', 'setting_value' => !empty($data['enabled']) ? '1' : '0']);
            return $this->reply(200, ['message' => 'Booking availability updated.']);
        }
        if ($action === 'category') {
            $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT) ?: null;
            $name = trim((string) ($data['name'] ?? '')); $description = trim((string) ($data['description'] ?? ''));
            $price = ($data['base_price'] ?? '') === '' ? null : filter_var($data['base_price'], FILTER_VALIDATE_FLOAT);
            $duration = filter_var($data['duration_minutes'] ?? 60, FILTER_VALIDATE_INT);
            if ($name === '' || mb_strlen($name) > 100 || $price === false || ($price !== null && $price < 0) || !$duration || $duration > 1440 || mb_strlen($description) > 500) return $this->reply(422, ['error' => 'Enter a valid service, price and duration.']);
            $fields = ['name' => $name, 'description' => $description, 'base_price' => $price, 'duration_minutes' => $duration, 'is_active' => !empty($data['is_active']) ? 1 : 0];
            if ($id) $db->table('service_categories')->where('id', $id)->update($fields); else $db->table('service_categories')->insert($fields);
            return $this->reply(200, ['message' => 'Service settings saved.']);
        }
        return $this->reply(422, ['error' => 'Unknown settings action.']);
    }

    public function notify(): ResponseInterface
    {
        $error = $this->checkFormRequest(); if ($error) return $error;
        $user = $this->signedIn(); if ($user instanceof ResponseInterface) return $user;
        if ($user['role'] !== 'admin') return $this->reply(403, ['error' => 'Admin access required.']);
        $data = $this->body(); if ($data instanceof ResponseInterface) return $data;
        $title = trim((string) ($data['title'] ?? '')); $message = trim((string) ($data['message'] ?? ''));
        $rawRecipient = trim((string) ($data['user_id'] ?? ''));
        $recipient = $rawRecipient === '' ? null : filter_var($rawRecipient, FILTER_VALIDATE_INT);
        if ($rawRecipient !== '' && !$recipient) return $this->reply(422, ['error' => 'Choose a valid customer.']);
        if ($title === '' || mb_strlen($title) > 160 || $message === '' || mb_strlen($message) > 1000) return $this->reply(422, ['error' => 'Enter an alert title and message.']);
        $builder = db_connect()->table('users')->select('users.id')->join('user_preferences', 'user_preferences.user_id=users.id', 'left')->where('users.role', 'customer')->where('users.is_active', 1)
            ->groupStart()->where('user_preferences.promotions', 1)->orWhere('user_preferences.promotions', null)->groupEnd();
        if ($recipient) $builder->where('id', $recipient);
        $rows = $builder->get()->getResultArray();
        foreach ($rows as $row) db_connect()->table('notifications')->insert(['user_id' => $row['id'], 'title' => $title, 'message' => $message, 'kind' => 'promotion']);
        return $this->reply(201, ['message' => count($rows) . ' customer alert(s) sent.']);
    }

    public function ticketReply(): ResponseInterface
    {
        $error = $this->checkFormRequest(); if ($error) return $error;
        $user = $this->signedIn(); if ($user instanceof ResponseInterface) return $user;
        if (!in_array($user['role'], ['admin','hr','dispatcher'], true)) return $this->reply(403, ['error' => 'Support team access required.']);
        $data = $this->body(); if ($data instanceof ResponseInterface) return $data;
        $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT); $reply = trim((string) ($data['reply'] ?? '')); $status = (string) ($data['status'] ?? 'open');
        if (!$id || mb_strlen($reply) > 5000 || !in_array($status, ['open','in_progress','resolved'], true)) return $this->reply(422, ['error' => 'Enter a valid ticket update.']);
        $db = db_connect(); $ticket = $db->table('support_tickets')->where('id', $id)->get()->getRowArray();
        if (!$ticket) return $this->reply(404, ['error' => 'Ticket not found.']);
        $db->table('support_tickets')->where('id', $id)->update(['staff_reply' => $reply ?: null, 'status' => $status]);
        $db->table('notifications')->insert(['user_id' => $ticket['user_id'], 'title' => 'Support ticket updated', 'message' => 'There is an update to your support request: ' . $ticket['subject'], 'kind' => 'support']);
        return $this->reply(200, ['message' => 'Ticket updated.']);
    }

    private function signedIn(): array|ResponseInterface
    {
        $user = (new LocalAuth())->currentUser();
        return $user ?? $this->reply(401, ['error' => 'Please sign in to continue.']);
    }

    private function notifyUser($db, int $userId, string $title, string $message, string $kind, ?string $preference = null): void
    {
        if ($preference !== null) {
            $settings = $db->table('user_preferences')->where('user_id', $userId)->get()->getRowArray();
            if ($settings && empty($settings[$preference])) return;
        }
        $db->table('notifications')->insert(['user_id' => $userId, 'title' => $title, 'message' => $message, 'kind' => $kind]);
    }

    private function body(): array|ResponseInterface
    {
        $data = $this->request->getJSON(true);
        return is_array($data) ? $data : $this->reply(400, ['error' => 'Invalid request.']);
    }

    private function analytics($db): array
    {
        return [
            'revenue' => $db->table('service_payments')->selectSum('amount')->where('status', 'paid')->get()->getRow('amount') ?? 0,
            'bookings' => $db->table('service_bookings')->countAllResults(),
            'customers' => $db->table('users')->where('role', 'customer')->countAllResults(),
            'active_customers' => $db->table('users')->where(['role' => 'customer', 'is_active' => 1])->countAllResults(),
            'customer_activity' => $db->query("SELECT DATE_FORMAT(created_at,'%Y-%m') AS month, COUNT(*) AS total FROM users WHERE role='customer' AND created_at >= DATE_SUB(UTC_DATE(), INTERVAL 6 MONTH) GROUP BY DATE_FORMAT(created_at,'%Y-%m') ORDER BY month")->getResultArray(),
            'services' => $db->table('service_bookings b')->select('c.name, COUNT(b.id) AS total')->join('service_categories c', 'c.id=b.category_id')->groupBy('c.id')->get()->getResultArray(),
            'revenue_by_month' => $db->query("SELECT DATE_FORMAT(paid_at,'%Y-%m') AS month, SUM(amount) AS total FROM service_payments WHERE status='paid' AND paid_at >= DATE_SUB(UTC_DATE(), INTERVAL 6 MONTH) GROUP BY DATE_FORMAT(paid_at,'%Y-%m') ORDER BY month")->getResultArray(),
        ];
    }
}
