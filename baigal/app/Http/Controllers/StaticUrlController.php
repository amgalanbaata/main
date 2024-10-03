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
            'soil' => asset('assets/Хөрснийбохирдлынмэдээлэл.pdf'),
            'companies' => asset('assets/Цахимномынсан.pdf.pdf'),
            'list' => asset('assets/Стандартууд.pdf'),
            'ubSoil' => asset('assets/Хөрснийлаборатори,шинжилгээнийгазар.pdf'),
            'contact-us' => asset('assets/Стандартыншаардлагахангасанариунцэврийнбайгууламжийнмэдээлэллавалгаа.pdf')
        ];

        $pdfUrl = $pdfMap[$type] ?? asset('assets/default.pdf');

        // Pass the PDF URL to the view
        return view('admin.staticUrls.pdfUrls', ['pdfUrl' => $pdfUrl]);
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
