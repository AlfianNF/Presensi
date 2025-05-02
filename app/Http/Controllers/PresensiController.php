<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Presensi;
use Illuminate\Http\Request;
use App\Models\SettingPresensi;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Config;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {    
        $modelClass = Presensi::class;
    
        $query = $modelClass::query();
    
        $filters = $request->only($modelClass::getAllowedFields('filter'));
        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                $query->where($field, 'LIKE', "%{$value}%");
            }
        }
    
        $search = $request->input('search');
        if ($search) {
            $allowedSearch = $modelClass::getAllowedFields('search');
            $query->where(function ($q) use ($search, $allowedSearch) {
                foreach ($allowedSearch as $field) {
                    if ($field === 'id_user') {
                        $q->orWhereHas('user', function ($uq) use ($search) {
                            $uq->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                        });
                    } else {
                        $q->orWhereRaw("LOWER(CAST($field AS TEXT)) LIKE ?", ["%" . strtolower($search) . "%"]);
                    }
                }
            });
        }

    
        $query->with((new $modelClass)->getRelations());
    
        $data = $query->orderBy('created_at', 'DESC')->get();
    
        
        if ($data->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data.',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $modelClass = Presensi::class;

        try {
            $rules = $modelClass::getValidationRules('add');
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $data = $request->all();

            $timezone = Config::get('app.timezone');
            $now = Carbon::now($timezone);

            $settingPresensi = SettingPresensi::find($data['id_setting']);
            if (!$settingPresensi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Setting Presensi tidak ditemukan.',
                ], 404);
            }

            $jamMasukSetting = Carbon::parse($settingPresensi->waktu, $timezone);
            $data['jam_masuk'] = $now->format('H:i:s');

            $diffInMinutes = abs($now->diffInMinutes($jamMasukSetting));

            if ($diffInMinutes > 60) {
                $data['status'] = 'terlambat';
            } else {
                $data['status'] = 'tepat waktu';
            }

            $presensi = $modelClass::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Presensi berhasil dibuat.',
                'data' => $presensi,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada database.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $modelClass = Presensi::class;

        $data = $modelClass::with((new $modelClass)->getRelations())->find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $modelClass = Presensi::class;

    try {
        $rules = $modelClass::getValidationRules('edit');
        
        $timezone = Config::get('app.timezone');
        $request->merge([
            'jam_keluar' => Carbon::now($timezone)->format('H:i:s')
        ]);

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $presensi = $modelClass::find($id);

        if (!$presensi) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        $presensi->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui.',
            'data' => $presensi,
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal.',
            'errors' => $e->errors(),
        ], 422);
    } catch (QueryException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan pada database.',
            'error' => $e->getMessage(),
        ], 500);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan server.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $modelClass = Presensi::class;

        try {
            $presensi = $modelClass::find($id);

            if (!$presensi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan.',
                ], 404);
            }

            $presensi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.',
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada database.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function history(Request $request)
    {
        try {
            $userId = auth()->id();
            $timezone = Config::get('app.timezone');

            $month = $request->input('month', now($timezone)->month);
            $year = $request->input('year', now($timezone)->year);

            $startOfMonth = Carbon::createFromDate($year, $month, 1, $timezone)->startOfMonth();
            $endOfMonth = $startOfMonth->copy()->endOfMonth();

            $weeks = [];
            $current = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
            while ($current <= $endOfMonth) {
                $weeks[] = [
                    'start' => $current->copy(),
                    'end' => $current->copy()->endOfWeek(Carbon::SUNDAY),
                ];
                $current->addWeek();
            }

            $now = Carbon::now($timezone);
            $currentWeekIndex = null;
            foreach ($weeks as $index => $week) {
                if ($now->between($week['start'], $week['end'])) {
                    $currentWeekIndex = $index;
                    break;
                }
            }

            $weekNumber = $request->input('week', $currentWeekIndex !== null ? $currentWeekIndex + 1 : 1);

            if (!isset($weeks[$weekNumber - 1])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minggu tidak valid.',
                ], 400);
            }

            $range = $weeks[$weekNumber - 1];

            $presensi = Presensi::where('id_user', $userId)
                ->whereDate('created_at', '>=', $range['start'])
                ->whereDate('created_at', '<=', $range['end'])
                ->orderBy('created_at', 'ASC')
                ->get();

            return response()->json([
                'success' => true,
                'week' => [
                    'start' => $range['start']->toDateString(),
                    'end' => $range['end']->toDateString(),
                    'number' => $weekNumber
                ],
                'data' => $presensi
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada database.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
