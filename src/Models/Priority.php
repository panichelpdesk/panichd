<?php

namespace PanicHD\PanicHD\Models;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    protected $table = 'panichd_priorities';

    protected $fillable = ['name', 'color', 'position'];

    /**
     * Indicates that this model should not be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

	
	public function delete($tickets_new_priority_id = false)
	{
		if ($tickets_new_priority_id){
			foreach($this->tickets()->get() as $ticket){
				$ticket->priority_id = $tickets_new_priority_id;
				$ticket->save();
			}
		}else{
			
			$new_id = Self::whereNotIn('id',[$this->id])->first()->id;
			foreach($this->tickets()->get() as $ticket){
				$ticket->priority_id = $new_id;
				
				$ticket->save();
			}
		}
		
		parent::delete();
	}
	
    /**
     * Get related tickets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Ticket', 'priority_id');
    }
}
