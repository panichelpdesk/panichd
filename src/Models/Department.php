<?php

namespace Kordy\Ticketit\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'ticketit_departments';
    protected $fillable = ['department'];

    /**
     * Get directly associated users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('Kordy\Ticketit\Models\Agent', 'ticketit_department')->orderBy('name');
    }
	
	/*
	 * Get related departments on current one
	*/
	/*public function scopeRelated($query){
		
		$query->
	}*/
	
	/*
	 * Get main department of current one
	*/
	public function parent(){
		return $this->belongsTo('Kordy\Ticketit\Models\Department', 'department_id');
	}
	
	/*
	 * Get all sub-departments of current one
	*/
	public function children(){
		return $this->hasMany('Kordy\Ticketit\Models\Department', 'department_id', 'id');
	}
	
	/*
	 * Get all department / subdepartment related to current one
	 * For Parent: Get self + all children
	 * For child: Get self + parent
	 *
	 * @Return collection
	*/
	public function related(){
		$related = Collect([]);
		$related->push($this);
		$parent = $this->parent()->first();
		if ($parent){
			// Is Child
			$related->push($parent);
		}else{
			// Is Parent
			$related->push($this->children()->get());
		}
		return $related;
	}
}