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
    public $comments_per_ticket_min = 0; // Minimum number of generated comments per ticket
    public $comments_per_ticket_max = 3; // Maximum number of generated comments per ticket
    public $default_agent_password = 'demo'; // default demo agents accounts paasword
    public $default_user_password = 'demo'; // default demo users accounts paasword
    public $tickets_date_period = 270; // to go to past (in days) and start creating tickets since
    public $a_active_status_ids = [1,2,3,4]; // default status ids array for closed tickets
    public $a_closed_status_ids = [5, 6]; // default status ids array for closed tickets
    public $a_demo_categories = [
        '_Demo_Corporate_support',
        '_Demo_Human_Resources',
        '_Demo_Office_Supplies',
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

        // Create demo agents
		$a_agents = [];

        for ($a = 1; $a <= $this->agents_qty; $a++) {
            $email = 'agent'.$a.$this->email_domain;
			
			$agent_info = Models\Member::firstOrNew(['email' => $email]);
			$agent_info->name = $faker->name;
			$agent_info->panichd_agent = 1;
			$agent_info->password = Hash::make($this->default_agent_password);
			$agent_info->save();
			$a_agents[$agent_info->id] = $agent_info;
        }
		
        // Create demo categories
		$a_categories = $a_cat_id_agents_id = [];
        foreach ($this->a_demo_categories as $name) {
            $category = Models\Category::firstOrNew(['name'  => $name]);
			$category->color = $faker->hexcolor;
			$category->save();
			$a_categories[] = $category->id;
			
			$a_assigned_agents = [];
			for ($i=1; $i<=$this->agents_per_category; $i++){
				// Random assigned agents to the category
				$a_assigned_agents[] = array_rand($a_agents);
			}
            
            $category->agents()->attach($a_assigned_agents);
			
			$a_cat_id_agents_id[$category->id] = $a_assigned_agents;
        }
		
        // Create ticket priorities
        $this->call(BasicPriorities::class);
		
		// Generate Priorities array
		$a_priorities = [];
        foreach (Models\Priority::all() as $priority){
			$a_priorities[] = $priority->id;
		}
		
		// Create ticket statuses (we use them from public arrays with current BasicStatuses, divided on two arrays one for active tickets and another for complete ones)
		$this->call(BasicStatuses::class);

        for ($u = 1; $u <= $this->users_qty; $u++) {
            
			// Create users
			$email = 'user'.$u.$this->email_domain;
			
			$user_info = Models\Member::firstOrNew(['email' => $email]);
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
                $ticket->updated_at = $ticket->created_at;
				
				if (mt_rand(0,2)){
					// 66% of complete tickets
					$ticket->intervention = $faker->paragraphs(2, true);
					$ticket->intervention_html = nl2br($ticket->intervention);
					
					$minutes_random_complete = rand(1, $random_create*24*60);
					$completed_at = \Carbon\Carbon::now()->subMinutes($minutes_random_complete);
					$ticket->completed_at = $completed_at;
					$ticket->updated_at = $completed_at;
					$ticket->status_id = $this->a_closed_status_ids[array_rand($this->a_closed_status_ids)];
				}else{
					// 33% of active tickets
					if (rand(0,1)){
						$ticket->intervention = $faker->paragraphs(1, true);
						$ticket->intervention_html = nl2br($ticket->intervention);
					}
					
					$ticket->status_id = $this->a_active_status_ids[array_rand($this->a_active_status_ids)];
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
                        '-'.$random_create.' days', '-'.($random_create - floor($minutes_random_complete/60/24)).' days');
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
}
