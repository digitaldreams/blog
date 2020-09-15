<?php

namespace Blog\Console\Commands;

use Blog\Models\Category;
use Blog\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GenerateHtmlFileForPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:generate-html-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate html for all posts routes.';

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
     * @param \Blog\Models\Category             $category
     * @param \Blog\Models\Post                 $post
     * @param \Illuminate\Http\Client\Factory   $http
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return int
     */
    public function handle(Category $category, Post $post, Filesystem $filesystem, Application $app)
    {
        $categories = $category->newQuery()->get();
        foreach ($categories as $c) {
            $this->info($c->slug);
            try {
                $route = route('blog::frontend.blog.categories.index', ['category' => $c->slug]);
                $this->downloadAndSave($route, $filesystem, $app);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                continue;
            }
        }

        return 0;
    }

    /**
     * @param                                    $url
     * @param \Illuminate\Filesystem\Filesystem  $filesystem
     * @param \Illuminate\Foundation\Application $application
     *
     * @throws \Exception
     */
    private function downloadAndSave($url, Filesystem $filesystem, Application $application)
    {
        $pathInfo = parse_url($url);
        $path = $pathInfo['path'];
        $request = Request::create($path, 'GET');
        $response = $application->handle($request);
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            if (!file_exists(public_path($folder = pathinfo($path, PATHINFO_DIRNAME)))) {
                mkdir(public_path($folder), 755, true);
            }

            $filesystem->put(public_path($path), $response->getContent());
            $this->info('saving ' . $path);
        }
    }
}
