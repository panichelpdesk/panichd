<?php

namespace Kordy\Ticketit\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentPerson extends Model
{
    protected $table = 'ticketit_departments_persons';
    protected $fillable = ['person_id', 'department_id'];

    public function department(){
		return $this->BelongsTo('Kordy\Ticketit\Models\Department', 'department_id');
	}
}