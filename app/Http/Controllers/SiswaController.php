<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search'); // Pencarian optional
    
        $query = DB::table('siswas')
            ->leftJoin('kelas', 'siswas.kelas_id', '=', 'kelas.id')
            ->select(
                'siswas.id',
                'siswas.name',
                'kelas.kelas as kelas',
                'siswas.email',
                'siswas.birth_date',
                'siswas.gender',
                'siswas.parent',
                'siswas.phone_number',
                'siswas.addres'
            );
    
        // Filter berdasarkan pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('siswas.name', 'like', "%$search%")
                    ->orWhere('kelas.kelas', 'like', "%$search%")
                    ->orWhere('siswas.email', 'like', "%$search%");
            });
        }
    
        // Ambil semua data
        $data = $query->get();
    
        return response()->json([
            'data' => $data,
        ]);
    }
    


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $created = Carbon::now();

        $data = [
            'name' => $request->name,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            // 'class' => $request->class,
            'parent' => $request->parent,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'addres' => $request->addres,
            'kelas_id' => $request->kelas_id,
            'created_at' => $created
        ];

        $inserted = DB::table('siswas')->insert($data);
        if ($inserted) {
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'fail' => false
                ], 400); // Gunakan 400 untuk kesalahan validasi
            }
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $updated = Carbon::now();

        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string',
            'parent' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'email' => 'email',
            'addres' => 'nullable|string',
            'kelas_id' => 'nullable|integer',
        ]);

        DB::beginTransaction();

        try {
            $siswa->fill([
                'name' => $request->name,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'parent' => $request->parent,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'addres' => $request->addres,
                'kelas_id' => $request->kelas_id,
                'updated_at' => $updated,
            ]);

            if ($siswa->isDirty()) {
                $siswa->save();
            }

            if (($request->filled('name') || $request->filled('email')) && $siswa->user_id) {
                $user = $siswa->user;

                $dataToUpdate = [];
                if ($request->filled('name')) {
                    $dataToUpdate['name'] = $request->name;
                }
                if ($request->filled('email')) {
                    $dataToUpdate['email'] = $request->email;
                }

                $user->update($dataToUpdate);
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $siswa->fresh()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Siswa::find($id);

        if (!$user) {
            return response()->json(['message' => 'Siswa not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'Siswa deleted successfully']);
    }
}
