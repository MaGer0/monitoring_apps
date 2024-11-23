<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\DetailStudentMonitoring;
use App\Http\Resources\DetailStudentMonitoringResource;

class DetailStudentMonitoringController
{

    public function index($id)
    {
        $dsm = DetailStudentMonitoring::query()->where('monitoring_id', $id)->get();

        return DetailStudentMonitoringResource::collection($dsm->loadMissing(['student:id,nisn,name,class']));
    }

    public function store(Request $request, $id)
    {

        $validated = $request->validate([
            '*.students_nisn' => 'required|string',
            '*.keterangan' => 'required|string'
        ]);

        // Bulk Insert
        $now = Carbon::now();
        $detailStudentMonitoring = array_map(function ($dsm) use ($now, $id) {
            return [
                'monitoring_id' => $id,
                'students_nisn' => $dsm['students_nisn'],
                'keterangan' => $dsm['keterangan'],
                'created_at' => $now
            ];
        }, $validated);

        DetailStudentMonitoring::insert($detailStudentMonitoring);

        $newDsm  = DetailStudentMonitoring::where('monitoring_id', $id)->get();

        return DetailStudentMonitoringResource::collection($newDsm->loadMissing(['student:id,nisn,name,class']));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            '*.students_nisn' => 'required|string',
            '*.keterangan' => 'required|string'
        ]);

        $nisnInRequest = array_column($validated, 'students_nisn');

        DetailStudentMonitoring::where('monitoring_id', $id)
            ->whereNotIn('students_nisn', $nisnInRequest)
            ->delete();

        $now = Carbon::now();
        $detailStudentMonitoring = array_map(function ($dsm) use ($now, $id) {
            return [
                'monitoring_id' => $id,
                'students_nisn' => $dsm['students_nisn'],
                'keterangan' => $dsm['keterangan'],
                'updated_at' => $now
            ];
        }, $validated);

        DetailStudentMonitoring::upsert(
            $detailStudentMonitoring,
            ['students_nisn', 'monitoring_id'],
            ['keterangan', 'updated_at']
        );


        $newDsm  = DetailStudentMonitoring::where('monitoring_id', $id)->get();

        return DetailStudentMonitoringResource::collection($newDsm->loadMissing(['student:id,nisn,name,class']));
    }

    public function destroy($id)
    {
        $dsm = DetailStudentMonitoring::query()->where('monitoring_id', $id)->get();

        DB::table('detail_students_monitorings')->select()->where('monitoring_id', $id)->delete();

        return DetailStudentMonitoringResource::collection($dsm->loadMissing(['student:id,nisn,name,class']));
    }
}
