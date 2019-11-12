<?php

namespace PanicHD\PanicHD\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'panichd_categories';

    protected $fillable = ['name', 'color', 'create_level'];

    /**
     * Indicates that this model should not be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Delete all category relations prior of itself.
     */
    public function delete()
    {
        $this->tickets()->delete();
        $this->closingReasons()->delete();
        $this->agents()->detach();

        // Tags detach and delete
        $a_tags = [];
        foreach ($this->tags()->get() as $tag) {
            $a_tags[] = $tag->id;
        }
        if ($a_tags) {
            $this->tags()->detach();
            Tag::whereIn('id', $a_tags)->delete();
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
        return $this->hasMany('PanicHD\PanicHD\Models\Ticket', 'category_id');
    }

    /**
     * Get related agents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function agents()
    {
        return $this->belongsToMany('\PanicHDMember', 'panichd_categories_users', 'category_id', 'user_id')->withPivot('autoassign')->orderBy('name');
    }

    /**
     * Get related tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany('PanicHD\PanicHD\Models\Tag', 'taggable', 'panichd_taggables')->orderBy('name');
    }

    /**
     * Get related closing reasons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function closingReasons()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Closingreason', 'category_id')->orderBy('ordering');
    }

    /**
     * Get all visible categories for current user.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        if (auth()->user()->panichd_admin) {
            return $query;
        } else {
            return $query->whereHas('agents', function ($query) {
                $query->where('id', auth()->user()->id);
            });
        }
    }
}
