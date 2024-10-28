<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Model\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PostsExport;

class ReportController extends Controller
{
    public function index()
    {
        // if (Session::get('admin_token') == '') {

            $model = new Admin();
            $typeCounts = $model->getTypeCount(Session::get('admin_is'));
            $counts = $model->getCountsByStatusReport(Session::get('admin_is'));

            return view('admin.report', ['counts' => $counts, 'typeCounts' => $typeCounts]);
        // }
        // else {
        //     Session::forget('admin_token');
        //     return redirect('admin');
        // }
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];
        $actionType = $request->input('action_type');


        if ($actionType == 'excel') {
            // Generate Excel report
            return Excel::download(new PostsExport($startDate, $endDate), 'posts_report.xlsx');
        } else {
            // Generate HTML report
            $report = new Report($startDate, $endDate);
            $statusCounts = $report->getStatusCounts(Session::get('admin_is'), $startDate, $endDate);

            return view('admin.report', [
                'counts' => $statusCounts,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'showReport' => true
            ]);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
