<?php 

namespace App\Services\Accounts\Payloads;

class OrganizationAdminPayload 
{
    public readonly string $name;

    public readonly string $email;

    public readonly string $password;

    public function __construct( private readonly array $data )
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
    }
}