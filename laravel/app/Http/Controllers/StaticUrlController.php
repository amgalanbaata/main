<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Model\Location;

class StaticUrlController extends Controller
{
    public function pdfUrls(Request $request)
    {
        $type = $request->query('type');

        $pdfMap = [
            'standarts' => asset('assets/Стандартууд.pdf'),
            'laboratoryList' => asset('assets/Хөрснийлаборатори,шинжилгээнийгазар.pdf'),
            'documents' => asset('assets/Холбогдох баримт бичгүүд.pdf'),
            'soilPollution' => asset('assets/Хөрснийбохирдлынмэдээлэл.pdf'),
            'companies' => asset('assets/Стандартыншаардлагахангасанариунцэврийнбайгууламжийнмэдээлэллавалгаа.pdf')
        ];

        $pdfUrl = $pdfMap[$type] ?? asset('assets/default.pdf');

        // Pass the PDF URL to the view
        return view('admin.staticUrls.pdfUrls', ['pdfUrl' => $pdfUrl]);
    }

    public function contactUs()
    {
        return view('admin.staticUrls.contact-us');
    }

    public function standarts()
    {
        return view('admin.staticUrls.standarts');
    }

    public function electronicLibrery()
    {
        return view('admin.staticUrls.electronicLibrery');
    }

    public function ubSoil()
    {
        return view('admin.staticUrls.ubSoil');
    }

    public function documents()
    {
        return view('admin.staticUrls.documents');
    }

    public function laboratoryList()
    {
        return view('admin.staticUrls.laboratoryList');
    }

    public function soilPollution()
    {
        return view('admin.staticUrls.soilPollution');
    }

    public function map(Request $request)
    {
        $model = new Location();
        $location = [
            'latitude' => $request->query('lat'),
            'longitude' => $request->query('lon'),
        ];
        $data = $model->getLocation();

        return view('admin.staticUrls.map', ['location' => $location], ['data' => $data]);
    }
}
