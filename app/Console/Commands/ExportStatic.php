<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportStatic extends Command
{
    protected $signature = 'export:static
        {--skip-build : Skip npm run build}
        {--base-path= : Base path for GitHub Pages (e.g. /project-cinema/)}';
    protected $description = 'Export all pages as static HTML to the docs/ folder for GitHub Pages';

    private string $docsPath;

    public function handle(): int
    {
        $this->docsPath = base_path('docs');

        // Step 1: Build Vite assets
        if (!$this->option('skip-build')) {
            $this->info('Building Vite assets...');
            $result = null;
            $output = null;
            exec('npm run build 2>&1', $output, $result);
            if ($result !== 0) {
                $this->error('Vite build failed:');
                $this->line(implode("\n", $output));
                return Command::FAILURE;
            }
            $this->info('Vite build complete.');
        }

        // Step 2: Clean and create docs directory
        if (File::isDirectory($this->docsPath)) {
            File::deleteDirectory($this->docsPath);
        }
        File::makeDirectory($this->docsPath, 0755, true);

        // Step 3: Copy static assets
        $this->info('Copying static assets...');
        $this->copyAssets();

        // Step 4: Render all pages
        $this->info('Rendering pages...');
        $routes = $this->getRoutes();

        foreach ($routes as $route) {
            $this->renderPage($route['url'], $route['output']);
        }

        // Step 5: Create .nojekyll
        File::put($this->docsPath . '/.nojekyll', '');

        $this->newLine();
        $this->info('Static export complete! Files written to docs/');
        $this->line('Total pages: ' . count($routes));

        return Command::SUCCESS;
    }

    private function getRoutes(): array
    {
        $routes = [
            ['url' => '/', 'output' => 'index.html'],
            ['url' => '/404', 'output' => '404.html'],
        ];

        foreach (config('cinema.movies') as $slug => $movie) {
            $routes[] = [
                'url' => "/movie/{$slug}",
                'output' => "movie/{$slug}/index.html",
            ];
        }

        foreach (config('cinema.tags') as $slug => $tag) {
            $routes[] = [
                'url' => "/tag/{$slug}",
                'output' => "tag/{$slug}/index.html",
            ];
        }

        return $routes;
    }

    private function renderPage(string $url, string $outputFile): void
    {
        $this->line("  Rendering: {$url} → docs/{$outputFile}");

        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $request = \Illuminate\Http\Request::create($url, 'GET');

        $response = $kernel->handle($request);
        $html = $response->getContent();

        $html = $this->rewriteAssetUrls($html);

        $outputPath = $this->docsPath . '/' . $outputFile;
        $dir = dirname($outputPath);
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put($outputPath, $html);
        $kernel->terminate($request, $response);
    }

    private function rewriteAssetUrls(string $html): string
    {
        $appUrl = rtrim(config('app.url', 'http://localhost'), '/');
        $basePath = rtrim($this->option('base-path') ?: '/', '/') . '/';

        // Strip the app URL from all references, leaving just the path
        $html = str_replace($appUrl, '', $html);

        // Inject <base> tag after <head> for correct relative path resolution
        $html = preg_replace(
            '#(<head[^>]*>)#i',
            '$1' . "\n" . '    <base href="' . $basePath . '">',
            $html,
            1
        );

        // Make all asset/image/link paths root-relative (strip leading slash so base tag works)
        $html = preg_replace('#(href|src|content)="/(build|images)/#', '$1="$2/', $html);

        // Fix internal navigation links to use clean paths
        // Home link: / → index.html
        $html = str_replace('href="/"', 'href="index.html"', $html);
        $html = str_replace("href='/'", "href='index.html'", $html);

        // Movie links: /movie/slug → movie/slug/index.html
        $html = preg_replace('#href="/movie/([^"]+)"#', 'href="movie/$1/index.html"', $html);

        // Tag links: /tag/slug → tag/slug/index.html
        $html = preg_replace('#href="/tag/([^"]+)"#', 'href="tag/$1/index.html"', $html);

        // Fix any remaining bare "/" href (from url('/'))
        $html = preg_replace('#href="(?:http://localhost)?"#', 'href="index.html"', $html);

        return $html;
    }

    private function copyAssets(): void
    {
        $buildSource = public_path('build');
        if (File::isDirectory($buildSource)) {
            File::copyDirectory($buildSource, $this->docsPath . '/build');
        }

        $imagesSource = public_path('images');
        if (File::isDirectory($imagesSource)) {
            File::copyDirectory($imagesSource, $this->docsPath . '/images');
        }
    }
}
