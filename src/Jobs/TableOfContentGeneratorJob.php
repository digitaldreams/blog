<?php

namespace Blog\Jobs;

use Blog\Models\Post;
use Blog\Services\TableOfContent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Tidy;

class TableOfContentGeneratorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Post
     */
    protected $post;

    /**
     * Create a new job instance.
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tidy_options = ['indent' => 2]; // equivalent of auto
        $tidy = new tidy();
        $tidy->parseString($this->post->body, $tidy_options);
        $tidy->cleanRepair();
        $tableOfContent = new TableOfContent($tidy);
        $this->post->table_of_content = $tableOfContent->process();
        $this->post->body = $tableOfContent->anchorLinkToHeadings();
        $this->post->save();
    }
}
