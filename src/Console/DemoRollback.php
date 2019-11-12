<?php

namespace PanicHD\PanicHD\Console;

use Illuminate\Console\Command;
use PanicHD\PanicHD\Models;

class DemoRollback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'panichd:demo-rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Undo all the elements created by DemoDataSeeder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('');
        $this->info('*');
        $this->info('* '.trans('panichd::console.demo-rollback'));
        $this->info('*');
        $this->info('');

        $this->info(trans('panichd::console.demo-rollback-description'));
        $this->info(' - '.trans('panichd::console.demo-rollback-info-categories'));
        $this->info(' - '.trans('panichd::console.demo-rollback-info-users'));
        $this->info('');
        $this->info(trans('panichd::console.demo-rollback-kept'));
        $this->info(' - '.trans('panichd::console.demo-rollback-priorities'));
        $this->info(' - '.trans('panichd::console.demo-rollback-statuses'));

        $options = [
            trans('panichd::console.continue-question-yes'),
            trans('panichd::console.continue-question-no'),
        ];

        $answer = $this->choice(trans('panichd::console.continue-question'), $options, 1);

        if ($answer != trans('panichd::console.continue-question-yes')) {
            $this->info(trans('panichd::console.command-aborted'));

            return false;
        }

        // Delete demo categories and tickets
        $o_categories = Models\Category::where('name', 'like', '_Demo_%');
        if ($o_categories->count() == 0) {
            $this->info(trans('panichd::console.demo-categories-not-found'));
        } else {
            foreach ($o_categories->get() as $category) {
                $category->delete();
            }

            if (Models\Category::count() == 0) {
                Models\Category::truncate();
                Models\Closingreason::truncate();
                Models\Tag::truncate();

                // Active agents deletion
                foreach (\PanicHDMember::agents()->get() as $member) {
                    $member->panichd_agent = 0;
                    $member->save();
                }
            }

            if (Models\Ticket::count() == 0) {
                Models\Attachment::truncate();
                Models\Comment::truncate();
                Models\Ticket::truncate();
            }
        }

        // Delete demo users
        $o_members = \PanicHDMember::where('email', 'like', '%@demodataseeder.com');
        if ($o_members->count() == 0) {
            $this->info(trans('panichd::console.demo-users-not-found'));
        } else {
            foreach ($o_members->get() as $member) {
                $member->delete();
            }

            if (\PanicHDMember::count() == 0) {
                PanicHDMember::truncate();
            }
        }

        $this->info(trans('panichd::console.done'));
    }
}
