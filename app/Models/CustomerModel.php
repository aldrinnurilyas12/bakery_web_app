<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // harus ini
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CustomerModel extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'customer'; // nama tabel

    protected $fillable = [
        'customer_code',
        'name',
        'address',
        'email',
        'birth_date',
        'password',
        'phone_number',
        'qr_code',
        'member_date',
        'status',
        'account_email_verified',
        'account_email_verified_at',
        'google_id',
        'created_at',
    ];

    protected $hidden = [
        'password',       // jangan tampilkan password
        'remember_token', // wajib untuk auth guard
    ];
}
