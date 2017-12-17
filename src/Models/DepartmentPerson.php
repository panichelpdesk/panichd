<?php

namespace PanicHD\PanicHD\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentPerson extends Model
{
    protected $table = 'panichd_departments_persons';
    protected $fillable = ['person_id', 'department_id'];

    public function department(){
		return $this->BelongsTo('PanicHD\PanicHD\Models\Department', 'department_id');
	}
}