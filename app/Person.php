<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $touches = ['project'];

    public function path()
    {
        return '/projects/' . $this->project_id . '/persons/' . $this->id;
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function completeName()
    {
        return $this->name . ' ' . $this->firstname;
    }
}
