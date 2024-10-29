<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Model\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PostsExport;
use App\Models\Model\Location;

class ReportController extends Controller
{
    public function index()
    {
        if (Session::get('admin_token') != '') {
            $tcode = Session::get('admin_is');
            // $tcode = 0;
            $model = new Admin();
            $typeCounts = $model->getTypeCount($tcode);
            $startDate = '';
            $endDate = '';
            $counts = $model->getCountsByStatusReport($tcode);

            $model = new Report();
            $registeredLocation = $model->getLocationCounts($tcode);
            // dd($tcode);

            return view('admin.report', ['counts' => $counts, 'typeCounts' => $typeCounts, 'startDate' => $startDate,
                'endDate' => $endDate, 'registeredLocation' => $registeredLocation]);
        }
        else {
            Session::forget('admin_token');
            return redirect('admin');
        }
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];

            // Generate HTML report
            $report = new Report($startDate, $endDate);
            $statusCounts = $report->getStatusCounts(Session::get('admin_is'), $startDate, $endDate);
            // dd($startDate);

            return view('admin.report', [
                'counts' => $statusCounts,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'showReport' => true
            ]);
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
