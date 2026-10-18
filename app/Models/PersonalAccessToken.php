<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model; // For SQL
use Laravel\Sanctum\PersonalAccessToken as SanctumToken;
use MongoDB\Laravel\Eloquent\DocumentModel;

class PersonalAccessToken extends SanctumToken
{
    use DocumentModel;

    protected $connection = 'mongodb';
    protected $table = 'personal_access_tokens';
    protected $primarykey = "_id";
    protected $keyType = 'string';
}
