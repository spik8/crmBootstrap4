<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department_info;
use App\HourReport;
use App\Pbx_report_extension;
use Session;
use App\User;


class ScreensController extends Controller
{
    /**
     * @param $id
     * @return view, array, array
     * Metoda zwraca widok monitorów(monitors) wraz z tablicą wypełnioną rekordami konsultantów i
     * tablicą wypełnioną rekordami wydziałów
     */
    public function monitorMethod($id) {
        $hour = date('H'); //03
        $hour = $hour . ":00:00"; //03:00:00
        $date = date("Y-m-d"); //2000-10-11
        $userTable = [];
        $reportTable = [];

        $givenUsers = $this::getPbxRecords($date, $hour);
        $userTable = $this->filingArrayDI($givenUsers, $userTable, $id);
        $report = $this::getHourReportRecords($date, $hour);
        $reportTable = $this::filingArrayDT($report, $reportTable, 2);

        return view('screens.monitors')->with('userTable', $userTable)
            ->with('reportTable', $reportTable);
    }

    /**
     * @param Request $request
     * @return view, department_info data
     * Metoda zwraca widok tabeli monitorów(screen_table) oraz dane dot. poszczególnych departamentów
     */
    public function screenMethod(Request $request) {
        $department_info = Department_info::all();
        return view('screens.screen_table')->with('dane', $department_info);
    }

    /**
     * @param $date
     * @param $hour
     * @return records
     * Zwraca rekordy z tabeli pbx_report_extension, o podanej dacie i godzinie, posortowane po "average"
     */
    public function getPbxRecords($date, $hour) {
        return Pbx_report_extension::where('report_date', '=', $date)
            ->where('report_hour', '=', $hour)
            ->orderBy('average', 'DESC')
            ->get();
    }

    /**
     * @param $date
     * @param $hour
     * @return records
     * Zwraca rekordy z tabeli hour_report, o podanej dacie i godzinie
     */
    public function getHourReportRecords($date, $hour) {
        return HourReport::where('hour', $hour)
            ->where('report_date', $date)
            ->orderBy('average', 'DESC')
            ->get();
    }

    /**
     * @param $records
     * @param $arr
     * @param $id
     * @return filled array
     * Zwraca tablice wypełnioną rekordami, uwarunkowanymi department_info_id = $id
     */
    public function filingArrayDI($records, $arr, $id) {
        foreach($records as $item) {
            if(is_object($item->user)) {
                if($item->user->department_info_id == $id) {
                    array_push($arr, $item);
                }
            }
        }
        return $arr;
    }

    /**
     * @param $records
     * @param $arr
     * @param $id
     * @return filled array
     * Zwraca tablice wypełnioną rekordami, uwarunkowanymi department_type_id = $id
     */
    public function filingArrayDT($records, $arr, $id) {
        foreach($records as $r) {
            if(is_object($r)) {
                if($r->department_info->id_dep_type == $id) {
                    array_push($arr, $r);
                }
            }
        }
        return $arr;
    }

    /**
     * THis method return necesary data for displaying charts
     */
    public function showScreensGet() {
        $today = date("Y-m-d"); //2000-10-11
        $reportData = HourReport::where('report_date', '=', $today)->get();
        $department_info = Department_info::where('id_dep_type', '=', '2')->get();
        return view('screens.charts')->with('reportData', $reportData)->with('department_info', $department_info);
    }
}
