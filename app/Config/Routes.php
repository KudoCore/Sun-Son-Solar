<?php
use CodeIgniter\Router\RouteCollection;
/** @var RouteCollection $routes */
// Explicit routes prevent accidental exposure of controller methods.
$routes->setAutoRoute(false);
$routes->get('/', 'Pages::home');
$routes->get('index.html', 'Pages::home');
$routes->get('register', 'Pages::registration');
$routes->get('registration.html', 'Pages::registration');
$routes->get('api/session', 'AuthApi::session');
$routes->get('api/public-config', 'RegistrationApi::config');
$routes->post('api/register', 'RegistrationApi::register');
$routes->set404Override('App\\Controllers\\Pages::notFound');

// Local account routes use the same MySQL users table as registration.
$routes->get('login', 'Pages::login');
$routes->get('login.html', 'Pages::login');
$routes->get('account', 'Pages::account');
$routes->get('profile', 'Pages::profile');
$routes->get('lets-talk-solar', 'Pages::serviceRequest');
$routes->post('api/login', 'AuthApi::login');
$routes->post('api/logout', 'AuthApi::logout');
$routes->get('portal', 'Pages::portal');
$routes->get('api/portal/data', 'PortalApi::data');
$routes->post('api/portal/booking', 'PortalApi::booking');
$routes->post('api/portal/booking/cancel', 'PortalApi::cancelBooking');
$routes->post('api/portal/profile', 'PortalApi::profile');
$routes->post('api/portal/password', 'PortalApi::password');
$routes->post('api/portal/ticket', 'PortalApi::ticket');
$routes->post('api/portal/employee', 'PortalApi::employee');
$routes->post('api/portal/booking/update', 'PortalApi::updateBooking');
$routes->post('api/portal/checkin', 'PortalApi::checkin');
$routes->post('api/portal/report', 'PortalApi::report');
$routes->post('api/portal/announcement', 'PortalApi::announcement');
$routes->post('api/portal/settings', 'PortalApi::settings');
$routes->post('api/portal/notify', 'PortalApi::notify');
$routes->post('api/portal/ticket/reply', 'PortalApi::ticketReply');
$routes->post('api/portal/payment', 'PortalApi::payment');
$routes->post('api/portal/testimonial', 'PortalApi::testimonial');

// Local public information pages.
$routes->get('products', 'Pages::products');
$routes->get('services', 'Pages::services');
$routes->get('projects', 'Pages::projects');
$routes->get('about', 'Pages::about');

// Remaining public pages and local enquiry submission.
$routes->get('contact', 'Pages::contact');
$routes->get('faq', 'Pages::faq');
$routes->get('privacy', 'Pages::privacy');
$routes->get('terms', 'Pages::terms');
$routes->get('cookies', 'Pages::cookies');
$routes->get('refunds', 'Pages::refunds');
$routes->get('thank-you', 'Pages::thankYou');
$routes->post('api/enquiry', 'EnquiryApi::submit');
