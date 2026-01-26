<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ['job_position_id', 'applicant_id', 'cv_path', 'cover_letter', 'recruiter_note'];

    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class);
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function interview()
    {
        return $this->hasOne(Interview::class);
    }
}
