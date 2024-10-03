<?php

namespace App\Models\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use Carbon\Carbon;
use DB;


class Report extends Model


{
    use HasFactory;

    protected $table = 'posts';
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = Carbon::parse($startDate);
        $this->endDate = Carbon::parse($endDate);
    }

    public function getStatusCounts($tcode, $startDate, $endDate)
    {
        if($tcode == 0) {
            $counts = [
                'new' => DB::table('posts')->where('status', 1)->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
                'received' => DB::table('posts')->where('status', 2)->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
                'resolved' => DB::table('posts')->where('status', 3)->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
                'rejected' => DB::table('posts')->where('status', 4)->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            ];
        } else {
            $counts = [
                'new' => DB::table('posts')->where('status', 1)->where('type', $tcode)->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
                'received' => DB::table('posts')->where('status', 2)->where('type', $tcode)->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
                'resolved' => DB::table('posts')->where('status', 3)->where('type', $tcode)->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
                'rejected' => DB::table('posts')->where('status', 4)->where('type', $tcode)->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            ];
        }
        return $counts;
    }

    public function reportExport($tcode)
    {
        $query = DB::table('posts')
            ->select('name', 'number', 'status', 'comment', 'admin_comment', 'type', 'created_at', 'updated_at')
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);
            // ->groupBy('name', 'number', 'status');

        if ($tcode != 0) {
            $query->where('type', $tcode);
        }

        return $query->get();
        // DB::raw('COUNT(*) as count')
    }

    // public function headings(): array
    // {
    //     return [
    //         'status',
    //         'count',
    //     ];
    // }
}
