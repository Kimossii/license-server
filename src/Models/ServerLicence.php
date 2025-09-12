<?php

//namespace App\Models;
namespace LicenseServer\Models; 

use Illuminate\Database\Eloquent\Model;

class ServerLicence extends Model
{
    //
    protected $table = 'server_licenses';
    protected $fillable = [
        'customer_registered_name',
        'client_name',
        'hardware_id',
        'expire_in',
        'license_code'
    ];

    protected $dates = [
        'expire_in',
    ];
}
