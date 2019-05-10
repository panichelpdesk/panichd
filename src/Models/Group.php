<?php

namespace PanicHD\PanicHD\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'panichd_groups';
    protected $fillable = ['name', 'full_name', 'group_id'];
	
	public $timestamps = false;

	/*
	 * Return ancestor of current Group
	 *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
	*/
	public function ancestor()
	{
		return $this->belongsTo('PanicHD\PanicHD\Models\Group', 'group_id');
	}
	
	/*
	 * Return all descendants of current Group
	 *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	public function descendants()
	{
		return $this->hasMany('PanicHD\PanicHD\Models\Group', 'group_id', 'id');
	}
	
    /**
     * Get Members that belong to $this Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany('\PanicHDMember', 'panichd_group_id')->orderBy('name');
    }
	
	/**
	 * Point if this is a main group
	 *
	 * @return bool
	*/
	public function is_main()
	{
		return is_null($this->group_id) ? true : false;
	}
	
	/*
	 * Get formatted name
	*/
	public function getName()
	{
		return ucwords(mb_strtolower($this->name));
	}
	
	/*
	 * Get group full name
	 * 
	 * @Return string
	*/
	public function getFullName()
	{
		return ucwords(mb_strtolower($this->full_name));
	}
	
	/*
	 * Get group shortened name
	 * 
	 * @Return string
	*/
	public function getShortName()
	{
		return ucwords(mb_strtolower($this->full_name));
	}
}