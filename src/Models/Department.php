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
			$related->push($this->ancestor()->first());
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
	 * Get formatted name
	*/
	public function getName()
	{
		return ucwords(mb_strtolower($this->name));
	}
	
	/*
	 * Get department name with format "Ancestor: Department"
	 * 
	 * @Return string
	*/
	public function getFullName()
	{
		$ancestor = ($this->is_main() ? '' : $this->ancestor()->first()->name . trans('panichd::lang.colon'));
		
		return ucwords(mb_strtolower($ancestor . $this->name));
	}
	
	/*
	 * Get Shortened department name with format "A: Department"
	 * 
	 * @Return string
	*/
	public function getShortName()
	{
		$ancestor = $this->is_main() ? '' : $this->ancestor()->first()->shortening . trans('panichd::lang.colon');
		
		return ucwords(mb_strtolower($ancestor . $this->name));
	}
}