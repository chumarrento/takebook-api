<?php


namespace App\Console\Commands;


use App\Entities\Book\Book;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ScheduleDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete books';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $books = Book::where('status_id', 3)->get();
        $today = new Carbon();
        $count = 0;
        if($books) {
            foreach ($books as $book) {
                if ($today->diffInDays($book->approved_at) >= 15) {
                    $images = $book->images;
                    if ($images) {
                        foreach ($images as $image) {
                            if (Storage::exists($image->cover)) {
                                Storage::delete($image->cover);
                            }
                        }
                    }
                    $book->delete();
                    $count++;
                }
            }
        }
        Log::info('Count', ['count' => $count]);
    }
}
