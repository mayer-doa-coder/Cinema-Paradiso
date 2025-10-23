<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserFavoriteMovie;
use App\Models\UserMovie;
use App\Models\UserMovieReview;
use App\Models\UserWatchlist;
use App\Services\MovieService;

class FixBrokenPosters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movies:fix-broken-posters {--check : Only check for broken posters without fixing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix broken movie poster URLs by fetching current paths from TMDB';

    protected $movieService;

    /**
     * Execute the console command.
     */
    public function handle(MovieService $movieService)
    {
        $this->movieService = $movieService;
        $checkOnly = $this->option('check');

        $this->info($checkOnly ? 'Checking for broken posters...' : 'Fixing broken movie posters...');
        $this->newLine();

        // Fix UserFavoriteMovie posters
        $this->info('Checking UserFavoriteMovie records...');
        $this->fixModelPosters(UserFavoriteMovie::class, $checkOnly);

        // Fix UserMovie posters
        $this->info('Checking UserMovie records...');
        $this->fixModelPosters(UserMovie::class, $checkOnly);

        // Fix UserMovieReview posters
        $this->info('Checking UserMovieReview records...');
        $this->fixModelPosters(UserMovieReview::class, $checkOnly);

        // Fix UserWatchlist posters
        $this->info('Checking UserWatchlist records...');
        $this->fixModelPosters(UserWatchlist::class, $checkOnly);

        $this->newLine();
        $this->info($checkOnly ? 'Check complete!' : 'All broken posters have been fixed!');

        return 0;
    }

    /**
     * Fix posters for a specific model
     */
    protected function fixModelPosters($modelClass, $checkOnly = false)
    {
        $uniqueMovies = $modelClass::select('movie_id', 'movie_poster')
            ->distinct()
            ->whereNotNull('movie_id')
            ->whereNotNull('movie_poster')
            ->get();

        $broken = 0;
        $fixed = 0;

        $progressBar = $this->output->createProgressBar($uniqueMovies->count());
        $progressBar->start();

        foreach ($uniqueMovies as $record) {
            $posterUrl = $this->generatePosterUrl($record->movie_poster);
            
            // Check if URL is accessible
            if ($posterUrl && !$this->isUrlAccessible($posterUrl)) {
                $broken++;
                
                if (!$checkOnly) {
                    // Fetch new poster from TMDB
                    try {
                        $movieDetails = $this->movieService->getMovieDetails($record->movie_id);
                        
                        if (isset($movieDetails['poster_path']) && !empty($movieDetails['poster_path'])) {
                            $modelClass::where('movie_id', $record->movie_id)
                                ->update(['movie_poster' => $movieDetails['poster_path']]);
                            $fixed++;
                        }
                    } catch (\Exception $e) {
                        // Silently continue on error
                    }
                }
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        if ($checkOnly) {
            $this->warn("  Found {$broken} broken poster(s)");
        } else {
            $this->info("  Fixed {$fixed} broken poster(s)");
        }
        
        $this->newLine();
    }

    /**
     * Generate poster URL from path
     */
    protected function generatePosterUrl($posterPath)
    {
        if (!$posterPath) {
            return null;
        }

        if (filter_var($posterPath, FILTER_VALIDATE_URL)) {
            return $posterPath;
        }

        if (strpos($posterPath, '/') === 0) {
            return "https://image.tmdb.org/t/p/w500" . $posterPath;
        }

        return "https://image.tmdb.org/t/p/w500/" . $posterPath;
    }

    /**
     * Check if URL is accessible
     */
    protected function isUrlAccessible($url)
    {
        $headers = @get_headers($url);
        if (!$headers) {
            return false;
        }
        
        $status = substr($headers[0], 9, 3);
        return $status == '200';
    }
}
