<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Excel;
use App\Reports;

class ReportsController extends Controller
{
    public function xls_report_summary(Request $request, Reports $report){

        $data = $report->getRequestSummary([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);
     
        Excel::create('TrainingRequests', function($excel) use ($data) {

            // Set the title
            $excel->setTitle('Training Request Summary');

            // Chain the setters
            $excel->setCreator('Fleet Training System')->setCompany('IPC');
            
            $excel->sheet('First sheet', function($sheet) use ($data) {
                
              
                $sheet->setStyle(array(
                    'font' => array(
                        'name'      =>  'Calibri',
                        'size'      =>  10
                    )
                ));
               
                $sheet->setFreeze('D3');
                $sheet->loadView('reports.template_request_summary',array('data' => $data));
                $sheet->setAllBorders('thick');
                $sheet->getStyle('B:H')->getAlignment()->setWrapText(true);
                $sheet->getStyle('J:R')->getAlignment()->setWrapText(true);

            });
        
        })->download('xlsx');
    }

    public function getTrainingRequestSummary(Request $request, Reports $report){
        $params = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ];

        $data = $report->getTrainingRequests($params);

        return $data;
    }
}
