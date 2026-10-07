<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/** Local settings. Only enter business details that have been verified. */
class Company extends BaseConfig
{
    public string $address = '';
    public string $phone = '';
    public string $mapsUrl = '';
    public string $responseTime = '';

    // The follow-up interview requests these employment profile fields.
    public bool $collectHrDetails = true;
}
