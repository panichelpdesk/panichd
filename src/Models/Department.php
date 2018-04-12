<?php

namespace PanicHD\PanicHD\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'panichd_departments';
    protected $fillable = ['name', 'shortening', 'department_id'];
	
	public $timestamps = false;

	/*
	 * Return ancestor of current Department
	 *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
	*/
	public function ancestor()
	{
		return $this->belongsTo('PanicHD\PanicHD\Models\Department', 'department_id');
	}
	
	
	/*
	 * Get ancestor Department of current one if it exists or return current
	*/
	public function getAncestor()
	{
		if ($this->is_main()){
			return $this;
		}else{
			$this->ancestor()->first();
		}
	}
	
	/*
	 * Return all descendants of current Department
	 *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	public function descendants()
	{
		return $this->hasMany('PanicHD\PanicHD\Models\Department', 'department_id', 'id');
	}
	
    /**
     * Get Members that belong to $this Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Member')->orderBy('name');
    }
	
	/*
	 * Get all departments in $this Department hierarchy
	 *
	 * For a main department: Returns self + all descendants
	 * For descendant: Returns self + ancestor
	 *
	 * @Return collection
	*/
	public function getRelated()
	{
		$related = Collect([]);
		$related->push($this);
		if ($this->is_main()){
			$related->push($this->descendants()->get());
		}else{
			$related->push($this->getAncestor());
		}
		
		return $related;
	}
	
	/**
	 * Point if this is a main department
	 *
	 * @return bool
	*/
	public function is_main()
	{
		return is_null($this->department_id) ? true : false;
	}
	
	/*
	 * Get formatted concatenation of department and sub1
	 *
	 * @param bool $long full text or shortening for department
	 * 
	 * @Return string
	*/
	public function resume ($long = false)
	{
		if ($this->department_id){
			return ($long ? ucwords(mb_strtolower($this->department)) : $this->shortening).trans('panichd::lang.colon').ucwords(mb_strtolower($this->sub1));
		}else{
			return ucwords(mb_strtolower($this->department));
		}
	}
	
	/*
	 * Get formatted department name
	 * 
	 * @Return string
	*/
	public function deptName(){
		return ucwords(mb_strtolower($this->department));
	}
	
	
	/*
	 * Get formatted department name as title
	 * 
	 * @Return string
	*/
	public function title ()
	{
		return trans('panichd::lang.department-shortening').trans('panichd::lang.colon').$this->deptName();
	}
}