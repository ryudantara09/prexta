<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    protected $fillable = ['title', 'description', 'city', 'country'];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
