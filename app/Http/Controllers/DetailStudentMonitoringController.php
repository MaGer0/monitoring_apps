<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Monitoring;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use function PHPSTORM_META\type;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use Illuminate\Auth\Events\Validated;
use App\Models\DetailStudentMonitoring;
use App\Http\Resources\DetailStudentMonitoringResource;

class DetailStudentMonitoringController
{
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            '*.monitoring_id' => 'required|integer|exists:monitorings,id',
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

        DB::table('detail_students_monitorings')->insert($detailStudentMonitoring);

        // return new DetailStudentMonitoringResource($validated);
        $resourceCollection = collect($detailStudentMonitoring)->map(function ($item) {
            return new DetailStudentMonitoringResource((object) $item); // Konversi ke object agar sesuai dengan Resource
        });

        return response()->json(['data' => $resourceCollection], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            '*.monitoring_id' => 'required|integer|exists:monitorings,id',
            '*.students_nisn' => 'required|string',
            '*.keterangan' => 'required|string'
        ]);

        $nisnInRequest = array_column($validated, 'students_nisn');
        dd($id);
        DetailStudentMonitoring::where('monitoring_id', $id)
            ->whereNotIn('students_nisn', $nisnInRequest)
            ->delete();

        $now = Carbon::now();
        $detailStudentMonitoring = array_map(function ($dsm) use ($now) {
            return [
                'monitoring_id' => $dsm['monitoring_id'],
                'students_nisn' => $dsm['students_nisn'],
                'keterangan' => $dsm['keterangan'],
                'updated_at' => $now
            ];
        }, $validated);

        DB::table('detail_students_monitorings')->upsert(
            $detailStudentMonitoring,
            ['studenddts_nisn', 'monitoring_id'],
            ['keterangan', 'updated_at']
        );

        $resourceCollection = collect($detailStudentMonitoring)->map(function ($item) {
            return new DetailStudentMonitoringResource((object) $item);
        });

        return response()->json(['data' => $resourceCollection], 201);
    }

    public function destroy($id)
    {
        $dsm = DB::table('detail_students_monitorings')->select()->where('monitoring_id', $id)->get();

        DB::table('detail_students_monitorings')->select()->where('monitoring_id', $id)->delete();

        return DetailStudentMonitoringResource::collection($dsm);
    }
}
