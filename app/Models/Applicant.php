<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $fillable = ['full_name', 'email'];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
