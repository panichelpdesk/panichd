<?php

namespace PanicHD\PanicHD\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public $email_domain = '@demodataseeder.com'; // the email domain name for demo accounts. Ex. user1@example.com
    public $agents_qty = 5; // number of demo agents accounts
    public $agents_per_category = 2; // number of demo agents per category (must be lower than $agents_qty)
    public $users_qty = 30; // number of demo users accounts
    public $tickets_per_user_min = 1; // Minimum number of generated tickets per user
    public $tickets_per_user_max = 5; // Maximum number of generated tickets per user
    public $comments_per_ticket_min = 0; // Minimum number of generated comments per ticket
    public $comments_per_ticket_max = 3; // Maximum number of generated comments per ticket
    public $default_agent_password = 'demo'; // default demo agents accounts paasword
    public $default_user_password = 'demo'; // default demo users accounts paasword
    public $tickets_date_period = 270; // to go to past (in days) and start creating tickets since
    public $a_active_status_ids = [1,2,3,4]; // default status ids array for closed tickets
    public $a_closed_status_ids = [5, 6]; // default status ids array for closed tickets
    public $categories = [
        'Technical'         => '#0014f4',
        'Billing'           => '#2b9900',
        'Customer Services' => '#7e0099',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $faker = \Faker\Factory::create();

        // create agents
        $agents_counter = 1;

        for ($a = 1; $a <= $this->agents_qty; $a++) {
            $agent_info = new \App\User();
            $agent_info->name = $faker->name;
            $agent_info->email = 'agent'.$agents_counter.$this->email_domain;
            $agent_info->panichd_agent = 1;
            $agent_info->password = Hash::make($this->default_agent_password);
            $agent_info->save();
            $agents[$agent_info->id] = $agent_info;
            $agents_counter++;
        }

        $counter = 0;
        // Create ticket categories
        foreach ($this->categories as $name => $color) {
            $category = \PanicHD\PanicHD\Models\Category::create([
                'name'  => $name,
                'color' => $color,
            ]);
            $agent = array_rand($agents, $this->agents_per_category);
            $category->agents()->attach($agent);
            $counter++;
        }

        // Create ticket priorities
        $this->call(BasicPriorities::class);
		
		// Create ticket statuses
		$this->call(BasicStatuses::class);
		
		// Counters
        $categories_qty = \PanicHD\PanicHD\Models\Category::count();
        $priorities_qty = \PanicHD\PanicHD\Models\Priority::count();

        $users_counter = 1;

        for ($u = 1; $u <= $this->users_qty; $u++) {
            
			// Create users
			$user_info = new \App\User();
            $user_info->name = $faker->name;
            $user_info->email = 'user'.$users_counter.$this->email_domain;
            $user_info->panichd_agent = 0;
            $user_info->password = Hash::make($this->default_user_password);
            $user_info->save();
            $users_counter++;

            $tickets_qty = rand($this->tickets_per_user_min, $this->tickets_per_user_max);
			
			// Create ticket
            for ($t = 1; $t <= $tickets_qty; $t++) {
                $rand_category = rand(1, $categories_qty);
                $priority_id = rand(1, $priorities_qty);

                $category = \PanicHD\PanicHD\Models\Category::find($rand_category);

                if (version_compare(app()->version(), '5.2.0', '>=')) {
                    $agents = $category->agents()->pluck('name', 'id')->toArray();
                } else { // if Laravel 5.1
                    $agents = $category->agents()->lists('name', 'id')->toArray();
                }

                $agent_id = array_rand($agents);
                $random_create = rand(1, $this->tickets_date_period);

                $ticket = new \PanicHD\PanicHD\Models\Ticket();
                $ticket->subject = $faker->text(50);
                $ticket->content = $faker->paragraphs(3, true);
                $ticket->html = nl2br($ticket->content);
                $ticket->priority_id = $priority_id;
                $ticket->creator_id = $user_info->id;
				$ticket->user_id = $user_info->id;
                $ticket->agent_id = $agent_id;
                $ticket->category_id = $rand_category;
                $ticket->created_at = \Carbon\Carbon::now()->subDays($random_create);
                $ticket->updated_at = $ticket->created_at;
				
				if (mt_rand(0,2)){
					// 66% of complete tickets
					$ticket->intervention = $faker->paragraphs(2, true);
					$ticket->intervention_html = nl2br($ticket->intervention);
					
					$minutes_random_complete = rand(1, $random_create*24*60);
					$random_complete = floor($minutes_random_complete/(60*24))+1;
					$completed_at = \Carbon\Carbon::now()->subMinutes();
					$ticket->completed_at = $completed_at;
					$ticket->updated_at = $completed_at;
					$ticket->status_id = array_rand($this->a_closed_status_ids);
				}else{
					// 33% of active tickets
					if (rand(0,1)){
						$ticket->intervention = $faker->paragraphs(1, true);
						$ticket->intervention_html = nl2br($ticket->intervention);
					}
					
					$ticket->status_id = array_rand($this->a_active_status_ids);
				}

                $ticket->save();

                $comments_qty = rand($this->comments_per_ticket_min,
                                    $this->comments_per_ticket_max);

                for ($c = 1; $c <= $comments_qty; $c++) {
                    if (is_null($ticket->completed_at)) {
                        $random_comment_date = $faker->dateTimeBetween(
                        '-'.$random_create.' days', 'now');
                    } else {
                        $random_comment_date = $faker->dateTimeBetween(
                        '-'.$random_create.' days', '-'.($random_create - $random_complete).' days');
                    }

                    $comment = new \PanicHD\PanicHD\Models\Comment();
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
}
