<?php

namespace PanicHD\PanicHD\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;
use PanicHD\PanicHD\Traits\ContentEllipse;

/**
 * @property Attachment[]|Collection attachments
 *
 * @see Ticket::attachments()
 */
class Ticket extends Model
{
    use ContentEllipse;

    protected $table = 'panichd_tickets';
    protected $dates = ['completed_at'];

    /**
     * Delete Ticket instance and related ones.
     */
    public function delete()
    {
        $a_errors = [];
        foreach ($this->allAttachments()->get() as $att) {
            $error = $att->delete();
            if ($error) {
                $a_errors[] = $error;
            }
        }

        if ($a_errors) {
            return implode('. ', $a_errors);
        }

        $this->tags()->detach();
        $this->comments()->delete();

        parent::delete();
    }

    /**
     * Check if ticket has comments.
     *
     * @return bool
     */
    public function hasComments()
    {
        return (bool) count($this->comments);
    }

    /**
     * Check if ticket is in Active list.
     *
     * @return bool
     */
    public function isActive()
    {
        $member = \PanicHDMember::findOrFail(auth()->user()->id);
        if ($member->currentLevel() >= 2) {
            return (bool) (is_null($this->completed_at) and $this->status_id != Setting::grab('default_status_id'));
        } else {
            return (bool) is_null($this->completed_at);
        }

        return (bool) $this->completed_at;
    }

    /**
     * Check if ticket is in Complete list.
     *
     * @return bool
     */
    public function isComplete()
    {
        return (bool) $this->completed_at;
    }

    /**
     * Check if ticket is in Newest list.
     *
     * @return bool
     */
    public function isNew()
    {
        $member = \PanicHDMember::findOrFail(auth()->user()->id);

        return (bool) ($member->currentLevel() >= 2 and is_null($this->completed_at) and $this->status_id == Setting::grab('default_status_id'));
    }

    /**
     * List of active tickets.
     *
     * @return Collection
     */
    public function scopeActive($query)
    {
        $query = $query->notComplete();

        if (is_null(auth()->user()) or \PanicHDMember::find(auth()->user()->id)->currentLevel() < 2) {
            return $query;
        } else {
            return $query->where('status_id', '!=', Setting::grab('default_status_id'));
        }
    }

    /**
     * List of completed tickets.
     *
     * @return Collection
     */
    public function scopeComplete($query)
    {
        return $query->whereNotNull('completed_at');
    }

    /**
     * List of NOT completed tickets.
     *
     * @return Collection
     */
    public function scopeNotComplete($query)
    {
        return $query->whereNull('completed_at');
    }

    /**
     * List of new tickets (active with status "new").
     *
     * @return Collection
     */
    public function scopeNewest($query)
    {
        return $query->notComplete()->where('status_id', Setting::grab('default_status_id'));
    }

    /**
     * Get specified ticket list (active or complete).
     *
     * @return Collection
     */
    public function scopeInList($query, $ticketList = 'active')
    {
        switch ($ticketList) {
            case 'newest':
                return $query->newest($query);
                break;
            case 'complete':
                return $query->complete($query);
                break;
            default:
                return $query->active($query);
                break;
        }
    }

    /**
     * Get Ticket status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo('PanicHD\PanicHD\Models\Status', 'status_id');
    }

    /**
     * Get Ticket priority.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function priority()
    {
        return $this->belongsTo('PanicHD\PanicHD\Models\Priority', 'priority_id');
    }

    /**
     * Get Ticket category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('PanicHD\PanicHD\Models\Category', 'category_id');
    }

    /**
     * Get Ticket creator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('\PanicHDMember', 'creator_id');
    }

    /**
     * Get Ticket owner as \PanicHDMember model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('\PanicHDMember', 'user_id');
    }

    /**
     * Get Ticket owner as PanicHDMember model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('\PanicHDMember', 'user_id');
    }

    /**
     * Get Ticket agent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo('\PanicHDMember', 'agent_id');
    }

    /**
     * Get Ticket comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Comment', 'ticket_id');
    }

    /**
     * Get email / members notified with comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasManyThrough
     */
    public function commentNotifications()
    {
        return $this->hasManyThrough('PanicHD\PanicHD\Models\CommentNotification', 'PanicHD\PanicHD\Models\Comment');
    }

    /**
     * Get Ticket comments updated recently.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recentComments()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Comment', 'ticket_id')->where('panichd_comments.updated_at', '>', Carbon::yesterday());
    }

    /*
     * Get Ticket internal notes only
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function internalNotes()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Comment', 'ticket_id')->where('panichd_comments.type', 'note');
    }

    /**
     * Ticket attachments (NOT including its comments attachments).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'ticket_id')
            ->whereNull('comment_id')->orderByRaw('CASE when mimetype LIKE "image/%" then 1 else 2 end');
    }

    /**
     * All related attachments for Ticket (+comment attachments).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allAttachments()
    {
        return $this->hasMany(Attachment::class, 'ticket_id');
    }

//    /**
    //     * Get Ticket audits
    //     *
    //     * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //     */
    //    public function audits()
    //    {
    //        return $this->hasMany('PanicHD\PanicHD\Models\Audit', 'ticket_id');
    //    }
    //

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
     * @see Illuminate/Database/Eloquent/Model::asDateTime
     */
    public function freshTimestamp()
    {
        return new Date();
    }

    /**
     * @see Illuminate/Database/Eloquent/Model::asDateTime
     */
    protected function asDateTime($value)
    {
        if (is_numeric($value)) {
            return Date::createFromTimestamp($value);
        } elseif (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value)) {
            return Date::createFromFormat('Y-m-d', $value)->startOfDay();
        } elseif (!$value instanceof DateTime) {
            $format = $this->getDateFormat();

            return Date::createFromFormat($format, $value);
        }

        return Date::instance($value);
    }

    /*
     * Improves Carbon diffForHumans to specify dates in text:
     *
     * - Yesterday, today, tomorrow
     * - Day of week for future dates within the next 6 days
     *
     * @param $date Eloquent property from timestamp field
     * @param $distant_dates_text boolean (distant dates show date info as descriptive text or date)
     *
     * @return string
    */
    public function getDateForHumans($date_field, $distant_dates_text = false)
    {
        if ($date_field == 'limit_date' and $this->limit_date == '') {
            // This is an empty limit_date
            $date = $this->start_date;
        } else {
            $date = $this->$date_field;
        }

        // Default date
        if ($distant_dates_text) {
            $date_text = Carbon::parse($date)->diffForHumans();
        } else {
            $date_text = date(trans('panichd::lang.date-format'), strtotime($date));
        }

        $parsed = Carbon::parse($date);

        // Real days  diff
        $days = Carbon::now()->startOfDay()->diffInDays($parsed->startOfDay(), false);

        if ($days == -1) {
            $text_to_format = trans('panichd::lang.yesterday');
        } elseif ($days === 0) {
            $text_to_format = trans('panichd::lang.today');
        } elseif ($days == 1) {
            $text_to_format = trans('panichd::lang.tomorrow');
        } elseif ($days > 1 and $parsed->diffInSeconds(Carbon::now()->addDays(6)->endOfDay(), false) >= 0) {

                // Within 6 days
            $text_to_format = trans('panichd::lang.day_'.$parsed->dayOfWeek);
        } elseif ($distant_dates_text) {

            // Real weeks diff
            $weeks = Carbon::now()->startOfWeek()->diffInWeeks($parsed->startOfWeek(), false);

            if ($weeks == 0) {
                $date_text = Carbon::now()->addDays($days)->diffForHumans();
            } elseif ($weeks >= -5 and $weeks <= 5) {
                $date_text = Carbon::now()->addWeeks($weeks)->diffForHumans();
            } else {
                // Real months diff
                $months = Carbon::now()->startOfMonth()->diffInMonths($parsed->startOfMonth(), false);
                $date_text = Carbon::now()->addMonths($months)->diffForHumans();
            }
        }

        if (isset($text_to_format)) {
            $date_text = trans('panichd::lang.datetime-text', [
                'date' => $text_to_format,
                'time' => $this->getTime($date_field),
                ]);
        }

        return $date_text;
    }

    /*
     * Get time from specified date field.
     *
     * - Output a time range if limit_date was specified and it's in same day as start date
     *
     * @param $date_field string
     *
     * @return string
    */
    public function getTime($date_field = 'limit_date')
    {
        if ($date_field == 'limit_date' and $this->limit_date == '') {
            // This is an empty limit_date
            $date = $this->start_date;
        } else {
            $date = $this->$date_field;
        }

        if ($date_field == 'limit_date' and $this->limit_date != '' and Carbon::parse($this->start_date)->startOfDay()->diffInDays(Carbon::parse($this->limit_date)->startOfDay()) == 0) {
            // Range for same day
            $start = strtotime($this->start_date);
            $limit = strtotime($this->limit_date);

            return date(date('i', $start) == '00' ? 'H' : 'H:i', $start)
                .'-'
                .date(date('i', $limit) == '00' ? 'H' : 'H:i', $limit);
        } else {
            // Normal time
            return date('H:i', strtotime($date));
        }
    }

    /**
     * Process start date and limit date and return a formatted div with resumed calendar information.
     *
     * @return string
     */
    public function getCalendarInfo($question_sign = false, $show = 'field')
    {
        $date_field = $title = $icon = '';
        $color = 'text-muted';
        $start_days_diff = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($this->start_date)->startOfDay(), false);
        if ($this->limit_date != '') {
            $limit_days_diff = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($this->limit_date)->startOfDay(), false);
            if ($limit_days_diff == 0) {
                $limit_seconds_diff = Carbon::now()->diffInSeconds(Carbon::parse($this->limit_date), false);
            }
        } else {
            $limit_days_diff = false;
        }

        if ($limit_days_diff < 0 or ($limit_days_diff == 0 and isset($limit_seconds_diff) and $limit_seconds_diff < 0)) {
            // Expired
            $date_field = 'limit_date';
            if ($limit_days_diff == 0) {
                $title = trans('panichd::lang.calendar-expired-today', ['time' => date('H:i', strtotime($this->limit_date))]);
            } else {
                $title = trans('panichd::lang.calendar-expired', ['description' => $this->getDateForHumans($date_field, true)]);
            }

            $icon = 'fa fa-exclamation-circle';
            $color = 'text-danger';
        } elseif ($limit_days_diff > 0 or $limit_days_diff === false) {
            if ($start_days_diff > 0) {
                // Scheduled
                $date_field = 'start_date';
                if ($limit_days_diff) {
                    if ($start_days_diff == $limit_days_diff) {
                        $title = trans('panichd::lang.calendar-scheduled', [
                            'date'  => date(trans('panichd::lang.date-format'), strtotime($this->start_date)),
                            'time1' => date('H:i', strtotime($this->start_date)),
                            'time2' => date('H:i', strtotime($this->limit_date)),
                        ]);
                    } else {
                        $title = trans('panichd::lang.calendar-scheduled-period', [
                            'date1' => $this->getDateForHumans('start_date'),
                            'date2' => $this->getDateForHumans('limit_date'), ]);
                    }
                    $icon = $start_days_diff == 1 ? 'fa fa-clock' : 'fa fa-calendar';
                    $color = 'text-info';
                } else {
                    $title = trans('panichd::lang.calendar-active-future', ['description' => $this->getDateForHumans($date_field, true)]);
                    $icon = 'fa fa-file';
                }
            } elseif ($limit_days_diff) {
                // Active with limit
                $date_field = 'limit_date';
                $title = trans('panichd::lang.calendar-expiration', ['description' => $this->getDateForHumans($date_field, true)]);
                $icon = 'fa fa-clock';
                $color = 'text-info';
            } else {
                // Active without limit
                $date_field = 'start_date';
                if ($start_days_diff == 0) {
                    $title = trans('panichd::lang.calendar-active-today', ['description' => $this->getDateForHumans($date_field, true)]);
                } else {
                    $title = trans('panichd::lang.calendar-active', ['description' => $this->getDateForHumans($date_field, true)]);
                }

                $icon = 'fa fa-file';
            }
        } else {
            // Due today
            $date_field = 'limit_date';
            if (Carbon::now()->diffInSeconds(Carbon::parse($this->start_date), false) < 0) {
                // Already started
                $title = trans('panichd::lang.calendar-expires-today', ['hour' => date('H:i', strtotime($this->limit_date))]);
            } else {
                // Scheduled for today but not yet started
                $title = trans('panichd::lang.calendar-scheduled-today', [
                    'time1' => date('H:i', strtotime($this->start_date)),
                    'time2' => date('H:i', strtotime($this->limit_date)),
                ]);
            }

            $icon = 'fa fa-exclamation-triangle';
            $color = 'text-warning';
        }

        if ($show == 'description') {
            // Date description
            return $title;
        } else {
            // Full field
            return "<span class=\"tooltip-info $color\" title=\"$title\" data-toggle=\"tooltip\" data-placement=\"bottom\"><span class=\"$icon\"></span> ".$this->getDateForHumans($date_field).' '.($question_sign ? '<span class="fa fa-question-circle"></span>' : '').'</span>';
        }
    }

    /**
     * Get abbreviated and localized last update time.
     *
     * @return string
     */
    public function getUpdatedAbbr()
    {
        $seconds = $this->updated_at->diffInSeconds();
        $days = $this->updated_at->diffInDays();

        if ($seconds < 60) {
            return $seconds.' '.trans('panichd::lang.second-abbr');
        } elseif ($seconds < 3600) {
            return $this->updated_at->diffInMinutes().' '.trans('panichd::lang.minute-abbr');
        } elseif ($days < 1) {
            return $this->updated_at->diffInHours().' '.trans('panichd::lang.hour-abbr');
        } elseif ($days < 15) {
            return $days.' '.trans('panichd::lang.day-abbr');
        } elseif ($days < 32) {
            return $this->updated_at->diffInWeeks().' '.trans('panichd::lang.week-abbr');
        } else {
            return $this->updated_at->diffInMonths().' '.trans('panichd::lang.month-abbr');
        }
    }

    /**
     * Get all user tickets.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeFromOwner($query, $id)
    {
        return $query->where('user_id', $id);
    }

    /**
     * Get tickets from specified Agent
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeFromAgent($query, $id)
    {
        return $query->where('agent_id', $id);
    }

    /**
     * Get all visible tickets for current user.
     * Includes: general user permissions and applied filters.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        if (auth()->user()->panichd_admin) {
            return $query;
        } elseif (auth()->user()->panichd_agent) {
            return $query->visibleForAgent(auth()->user()->id);
        } else {
            return $query->fromOwner(auth()->user()->id)->notHidden();
        }
    }

    /**
     * Get all visible tickets for agent.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeVisibleForAgent($query, $id = false)
    {
        if (!$id) {
            $id = auth()->user()->id;
        }
        $agent = \PanicHDMember::findOrFail($id);

        if ($agent->currentLevel() == 2) {
            // Depends on agent_restrict
            if (Setting::grab('agent_restrict') == 0) {
                // Returns all tickets on Categories where Agent with $id belongs to.
                return $query->whereHas('category', function ($q1) use ($id) {
                    $q1->whereHas('agents', function ($q2) use ($id) {
                        $q2->where('id', $id);
                    });
                });
            } else {
                // Returns all tickets Owned by Agent with $id only
                return $query->fromAgent($id);
            }
        } else {
            // Agent with currentLevel() == 1
            return $query->fromOwner($id)->notHidden()
                ->whereDoesntHave('category', function ($q1) use ($id) {
                    $q1->whereHas('agents', function ($q2) use ($id) {
                        $q2->where('id', $id);
                    });
                });
        }
    }

    /**
     * Filters to ticket list.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeFiltered($query, $ticketList, $filter = false)
    {
        $member = \PanicHDMember::find(auth()->user()->id);

        if ($member->currentLevel() == 1) {
            // If session()->has('panichd_filter_currentLevel')
            return $query->fromOwner(auth()->user()->id)->notHidden();
        } else {
            if (session()->has('panichd_filters')) {
                if ($ticketList != 'complete') {
                    // Calendar filter
                    if ((!$filter or $filter == 'calendar') and session()->has('panichd_filter_calendar')) {
                        $calendar_filter = session('panichd_filter_calendar');

                        if ($calendar_filter == 'expired') {
                            // Expired tickets
                            $query = $query->where('limit_date', '<', Carbon::now());
                        } elseif ($calendar_filter == 'not-scheduled') {
                            // Not scheduled tickets
                            $query = $query->whereNull('limit_date');
                        } else {
                            // All non expired tickets
                            $query = $query->where('limit_date', '>=', Carbon::now()->today());
                        }

                        switch ($calendar_filter) {
                            case 'today':
                                $query = $query->where('limit_date', '<', Carbon::now()->tomorrow());
                                break;

                            case 'tomorrow':
                                $query = $query->where('limit_date', '>=', Carbon::now()->tomorrow());
                                $query = $query->where('limit_date', '<', Carbon::now()->addDays(2)->startOfDay());
                                break;

                            case 'week':
                                if (Setting::grab('calendar_month_filter')) {
                                    $query = $query->where('limit_date', '<', Carbon::now()->endOfWeek());
                                }
                                break;

                            case 'month':
                                if (Setting::grab('calendar_month_filter')) {
                                    $query = $query->where('limit_date', '<', Carbon::now()->endOfMonth());
                                }
                                break;

                            case 'within-7-days':
                                if (!Setting::grab('calendar_month_filter')) {
                                    $query = $query->where('limit_date', '<', Carbon::now()->addDays(7)->endOfDay());
                                }
                                break;

                            case 'within-14-days':
                                if (Setting::grab('calendar_month_filter')) {
                                    $query = $query->where('limit_date', '<', Carbon::now()->addDays(14)->endOfDay());
                                }
                                break;
                        }
                    }
                }

                // Category filter
                if ((!$filter or $filter == 'category') and session()->has('panichd_filter_category')) {
                    $category = session('panichd_filter_category');
                    $query = $query->where('category_id', session('panichd_filter_category'));
                }

                // Agent filter
                if ((!$filter or $filter == 'agent') and session()->has('panichd_filter_agent')) {
                    $query = $query->fromAgent(session('panichd_filter_agent'));
                }

                // Owner filter
                if ((!$filter or $filter == 'owner') and session()->has('panichd_filter_owner')) {
                    if (session('panichd_filter_owner') == 'me') {
                        $query = $query->fromOwner(auth()->user()->id);
                    } else {
                        $query = $query->fromOwner(session('panichd_filter_owner'));
                    }
                }
            }

            if ($ticketList == 'complete' and (!$filter or $filter == 'year')) {
                // Year filter
                $query = $query->completedOnYear();
            }

            return $query;
        }
    }

    /**
     * Get all tickets in specified category.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeInCategory($query, $id = null)
    {
        if (isset($id)) {
            return $query->where('category_id', $id);
        } else {
            return $query;
        }
    }

    /**
     * Get tickets that are hidden for users.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeHidden($query)
    {
        return $query->where('hidden', '1');
    }

    /**
     * Get tickets that are not hidden for users.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeNotHidden($query)
    {
        return $query->where('hidden', '0');
    }

    /**
     * Get tickets completed in selected year.
     *
     * default is current year
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeCompletedOnYear($query, $year = false)
    {
        $query = $query->complete();

        if ($year) {
            // All years
            if ($year == 'all') {
                return $query;
            }
        } elseif (session()->has('panichd_filter_year')) {
            if (session('panichd_filter_year') == 'all') {
                // All years
                return $query;
            } else {
                // Filtered year
                $year = session('panichd_filter_year');
            }
        } else {
            // Current year
            $year = date('Y');
        }

        return $query->whereYear('completed_at', $year);
    }

    /**
     * Sets the agent with the lowest tickets assigned in specific category.
     *
     * @return Ticket
     */
    public function autoSelectAgent()
    {
        $cat_id = $this->category_id;
        $agents = Category::find($cat_id)->agents()->wherePivot('autoassign', '1')->with(['ticketsAsAgent' => function ($query) {
            $query->addSelect(['id', 'agent_id']);
        }])->get();
        $count = 0;
        $lowest_tickets = 1000000;
        // If no agent selected, select the admin
        $first_admin = \PanicHDMember::admins()->first();
        $selected_agent_id = $first_admin->id;
        foreach ($agents as $agent) {
            if ($count == 0) {
                $lowest_tickets = $agent->ticketsAsAgent->count();
                $selected_agent_id = $agent->id;
            } else {
                $tickets_count = $agent->ticketsAsAgent->count();
                if ($tickets_count < $lowest_tickets) {
                    $lowest_tickets = $tickets_count;
                    $selected_agent_id = $agent->id;
                }
            }
            $count++;
        }
        $this->agent_id = $selected_agent_id;

        return $this;
    }
}
