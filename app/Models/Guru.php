<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class Guru extends Model implements Authenticatable
{
    use AuthenticableTrait;
    
    use HasFactory;

    protected $table = 'guru';

    protected $guarded = [];

    protected $primaryKey = 'id_guru';
}
