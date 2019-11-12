<?php

namespace PanicHD\PanicHD\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use PanicHD\PanicHD\Models;

class DemoDataSeeder extends Seeder
{
    public $email_domain = '@demodataseeder.com'; // the email domain name for demo accounts. Ex. user1@example.com
    public $agents_qty = 5; // number of demo agents accounts
    public $agents_per_category = 2; // number of demo agents per category (must be lower than $agents_qty)
    public $users_qty = 30; // number of demo users accounts
    public $tickets_per_user_min = 1; // Minimum number of generated tickets per user
    public $tickets_per_user_max = 5; // Maximum number of generated tickets per user
    public $tags_per_ticket = 1;
    public $comments_per_ticket_min = 0; // Minimum number of generated comments per ticket
    public $comments_per_ticket_max = 3; // Maximum number of generated comments per ticket
    public $default_agent_password = 'demo'; // default demo agents accounts paasword
    public $default_user_password = 'demo'; // default demo users accounts paasword
    public $tickets_date_period = 270; // to go to past (in days) and start creating tickets since
    public $a_active_status_ids = [1, 2, 3, 4]; // default status ids array for closed tickets
    public $a_closed_status_ids = [5, 6]; // default status ids array for closed tickets
    public $a_demo_categories = [
        '_Demo_Corporate_support' => ['Hardware', 'Software', 'User training', 'e-mail', 'Internet'],
        '_Demo_Human_Resources'   => ['Timeoff', 'Certificate of discharge', 'Salary', 'Hiring', 'Dismissal'],
        '_Demo_Office_Supplies'   => ['New supply', 'Replacement', 'At technical service', 'Ready to deliver'],
    ];
    public $a_bg_color = ['#e6b8af', '#f4cccc', '#fce5cd', '#fff2cc', '#d9ead3', '#d0e0e3', '#c9daf8', '#cfe2f3', '#d9d2e9', '#ead1dc'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $faker = \Faker\Factory::create();

        // Create demo agents
        $a_agents = [];

        for ($a = 1; $a <= $this->agents_qty; $a++) {
            $email = 'agent'.$a.$this->email_domain;

            $agent_info = \PanicHDMember::firstOrNew(['email' => $email]);
            $agent_info->name = $faker->name;
            $agent_info->panichd_agent = 1;
            $agent_info->password = Hash::make($this->default_agent_password);
            $agent_info->save();
            $a_agents[$agent_info->id] = $agent_info;
        }

        // Create demo categories
        $a_categories = $a_cat_id_agents_id = [];
        foreach ($this->a_demo_categories as $name => $tags) {
            $category = Models\Category::firstOrNew(['name'  => $name]);
            $category->color = $faker->hexcolor;
            $category->save();
            $a_categories[] = $category->id;

            // Category agents
            $a_assigned_agents = [];
            for ($i = 1; $i <= $this->agents_per_category; $i++) {
                // Random assigned agents to the category
                $a_assigned_agents[] = array_rand($a_agents);
            }

            $category->agents()->attach($a_assigned_agents);

            $a_cat_id_agents_id[$category->id] = $a_assigned_agents;

            // Category tags
            foreach ($tags as $tag) {
                $tag = $category->tags()->create([
                    'name'       => $tag,
                    'bg_color'   => $this->a_bg_color[array_rand($this->a_bg_color)],
                    'text_color' => '#0c343d',
                ]);

                $a_cat_id_tags_id[$category->id][] = $tag->id;
            }
        }

        // Create ticket priorities
        $this->call(BasicPriorities::class);

        // Generate Priorities array
        $a_priorities = [];
        foreach (Models\Priority::all() as $priority) {
            $a_priorities[] = $priority->id;
        }

        // Create ticket statuses (we use them from public arrays with current BasicStatuses, divided on two arrays one for active tickets and another for complete ones)
        $this->call(BasicStatuses::class);

        // For usage in tickets loop
        $minutes_to_day_end = Carbon::now()->diffInMinutes(Carbon::now()->endOfDay());
        $minutes_per_day = 60 * 24;

        for ($u = 1; $u <= $this->users_qty; $u++) {

            // Create users
            $email = 'user'.$u.$this->email_domain;

            $user_info = \PanicHDMember::firstOrNew(['email' => $email]);
            $user_info->name = $faker->name;
            $user_info->panichd_agent = 0;
            $user_info->password = Hash::make($this->default_user_password);
            $user_info->save();

            $tickets_qty = rand($this->tickets_per_user_min, $this->tickets_per_user_max);

            // Create ticket
            for ($t = 1; $t <= $tickets_qty; $t++) {
                $random_create = rand(1, $this->tickets_date_period);

                $ticket = new Models\Ticket();
                $ticket->subject = $faker->text(50);
                $ticket->content = $faker->paragraphs(3, true);
                $ticket->html = nl2br($ticket->content);
                $ticket->priority_id = $a_priorities[array_rand($a_priorities)];
                $ticket->creator_id = $user_info->id;
                $ticket->user_id = $user_info->id;
                $category_id = $a_categories[array_rand($a_categories)];
                $ticket->category_id = $category_id;
                $ticket->agent_id = $a_cat_id_agents_id[$category_id][array_rand($a_cat_id_agents_id[$category_id])];
                $ticket->created_at = Carbon::now()->subDays($random_create);
                $ticket->start_date = $ticket->created_at;

                if (mt_rand(0, 2)) {
                    // 2/3 of the tickets have limit date
                    $calendar = mt_rand(0, 3);
                    $random_limit_seconds = rand(0, 86390);

                    // Distribution of each calendar status
                    switch ($calendar) {
                        case 0:
                            // Expired
                            $random_limit_days = rand(0, 8);
                            $ticket->limit_date = Carbon::now()->subDays($random_limit_days)->subSeconds($random_limit_seconds);
                            break;
                        case 1:
                            // Expires today or tomorrow
                            $random_limit_minutes = rand(0, $minutes_to_day_end + $minutes_per_day - 5);
                            $ticket->limit_date = Carbon::now()->addMinutes($random_limit_minutes);
                            break;
                        case 2:
                            // Expires this week
                            $random_limit_days = rand(0, 6);
                            $ticket->limit_date = Carbon::now()->addDays($random_limit_days);
                            break;
                        case 3:
                            // Expires this month
                            $random_limit_days = rand(7, 28);
                            $ticket->limit_date = Carbon::now()->addDays($random_limit_days);
                            break;
                    }
                } else {
                    // One third of the tickets that doesn't have limit date
                }

                if (mt_rand(0, 1)) {
                    // 50% of complete tickets
                    $ticket->intervention = $faker->paragraphs(2, true);
                    $ticket->intervention_html = nl2br($ticket->intervention);

                    $minutes_random_complete = rand(1, $random_create * 24 * 60);
                    $completed_at = \Carbon\Carbon::now()->subMinutes($minutes_random_complete);
                    $ticket->completed_at = $completed_at;
                    $ticket->updated_at = $completed_at;
                    $ticket->status_id = $this->a_closed_status_ids[array_rand($this->a_closed_status_ids)];
                } else {
                    // 50% of active tickets
                    if (rand(0, 1)) {
                        $ticket->intervention = $faker->paragraphs(1, true);
                        $ticket->intervention_html = nl2br($ticket->intervention);
                    }

                    $ticket->updated_at = $ticket->created_at;

                    $ticket->status_id = $this->a_active_status_ids[array_rand($this->a_active_status_ids)];
                }

                $ticket->save();

                // Tags
                for ($i = 1; $i <= $this->tags_per_ticket; $i++) {
                    $ticket->tags()->attach($a_cat_id_tags_id[$category_id][array_rand($a_cat_id_tags_id[$category_id])]);
                }

                $comments_qty = rand($this->comments_per_ticket_min,
                                    $this->comments_per_ticket_max);

                for ($c = 1; $c <= $comments_qty; $c++) {
                    if (is_null($ticket->completed_at)) {
                        $random_comment_date = $faker->dateTimeBetween(
                        '-'.$random_create.' days', 'now');
                    } else {
                        $random_comment_date = $faker->dateTimeBetween(
                        '-'.$random_create.' days', '-'.($random_create - floor($minutes_random_complete / 60 / 24)).' days');
                    }

                    $comment = new Models\Comment();
                    $comment->ticket_id = $ticket->id;
                    $comment->content = $faker->paragraphs(3, true);
                    $comment->html = nl2br($comment->content);

                    if ($c % 2 == 0) {
                        $comment->user_id = $ticket->user_id;
                    } else {
                        $comment->user_id = $ticket->agent_id;
                    }
                    $comment->created_at = $random_comment_date;
                    $comment->updated_at = $random_comment_date;
                    $comment->save();
                }
                $last_comment = $ticket->Comments->sortByDesc('created_at')->first();
                $ticket->updated_at = $last_comment['created_at'];
                $ticket->save();
            }
        }
    }

    protected function color_inverse($color)
    {
        $color = str_replace('#', '', $color);
        if (strlen($color) != 6) {
            return '000000';
        }
        $rgb = '';
        for ($x = 0; $x < 3; $x++) {
            $c = 255 - hexdec(substr($color, (2 * $x), 2));
            $c = ($c < 0) ? 0 : dechex($c);
            $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
        }

        return '#'.$rgb;
    }
}
