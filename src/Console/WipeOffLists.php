<?php

namespace PanicHD\PanicHD\Console;

use Illuminate\Console\Command;
use PanicHD\PanicHD\Models;

class WipeOffLists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'panichd:wipe-off-lists';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset desired list from PanicHD';

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
        $this->info('* '.trans('panichd::console.wipe-off-lists'));
        $this->info('*');
        $this->info('');
        $this->info(trans('panichd::console.wipe-off-lists-description'));

        $options = [
            trans('panichd::console.continue-question-abort'),
            trans('panichd::console.wipe-off-list-all'),
            trans('panichd::console.wipe-off-list-categories'),
            trans('panichd::console.wipe-off-list-priorities'),
            trans('panichd::console.wipe-off-list-statuses'),
        ];

        $answer = $this->choice(trans('panichd::console.wipe-off-wich-list-question'), $options, 0);

        if ($answer == trans('panichd::console.continue-question-abort')) {
            $this->info(trans('panichd::console.command-aborted'));

            return false;
        } else {
            $this->info(trans('panichd::console.process-started'));
            $this->info('');

            if (in_array($answer, [
                trans('panichd::console.wipe-off-list-all'),
                trans('panichd::console.wipe-off-list-categories'),
            ])) {
                // Categories deletion
                foreach (Models\Category::all() as $category) {
                    $category->delete();
                }
                Models\Category::truncate();
                Models\Closingreason::truncate();
                Models\Tag::truncate();

                // Active agents deletion
                foreach (\PanicHDMember::agents()->get() as $member) {
                    $member->panichd_agent = 0;
                    $member->save();
                }

                $this->info(trans('panichd::console.wipe-off-list-categories-done'));
            }

            if (in_array($answer, [
                trans('panichd::console.wipe-off-list-all'),
                trans('panichd::console.wipe-off-list-priorities'),
            ])) {
                // Priorities deletion
                foreach (Models\Priority::all() as $priority) {
                    $priority->delete();
                }
                Models\Priority::truncate();

                $this->info(trans('panichd::console.wipe-off-list-priorities-done'));
            }

            if (in_array($answer, [
                trans('panichd::console.wipe-off-list-all'),
                trans('panichd::console.wipe-off-list-statuses'),
            ])) {
                // Statuses deletion
                foreach (Models\Status::all() as $status) {
                    $status->delete();
                }
                Models\Status::truncate();

                $this->info(trans('panichd::console.wipe-off-list-statuses-done'));
            }
        }

        if ($answer == trans('panichd::console.wipe-off-list-all')) {
            $this->info('');
            $this->info(trans('panichd::console.done'));
        }
    }
}
