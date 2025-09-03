<?php

namespace App\Services;

use App\Models\TblRumahSakit;
use Illuminate\Pagination\LengthAwarePaginator;

class RumahSakitService
{
    /**
     * Get paginated rumah sakit data.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedData(int $perPage = 10): LengthAwarePaginator
    {
        return TblRumahSakit::orderBy('nama_rumah_sakit')->paginate($perPage);
    }

    /**
     * Search rumah sakit by query.
     *
     * @param string|null $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchData(?string $query)
    {
        return TblRumahSakit::when($query, function ($q) use ($query) {
            $q->search($query);
        })
            ->orderBy('nama_rumah_sakit')
            ->get();
    }

    /**
     * Create a new rumah sakit.
     *
     * @param array $data
     * @return TblRumahSakit
     * @throws \Exception
     */
    public function create(array $data): TblRumahSakit
    {
        try {
            return TblRumahSakit::create($data);
        } catch (\Exception $e) {
            throw new \Exception('Gagal menyimpan data rumah sakit: ' . $e->getMessage());
        }
    }

    /**
     * Update rumah sakit data.
     *
     * @param int $id
     * @param array $data
     * @return TblRumahSakit
     * @throws \Exception
     */
    public function update(int $id, array $data): TblRumahSakit
    {
        try {
            $rumahSakit = TblRumahSakit::findOrFail($id);
            $rumahSakit->update($data);
            return $rumahSakit;
        } catch (\Exception $e) {
            throw new \Exception('Gagal mengupdate data rumah sakit: ' . $e->getMessage());
        }
    }

    /**
     * Delete rumah sakit.
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id): bool
    {
        try {
            $rumahSakit = TblRumahSakit::findOrFail($id);
            return $rumahSakit->delete();
        } catch (\Exception $e) {
            throw new \Exception('Gagal menghapus data rumah sakit: ' . $e->getMessage());
        }
    }

    /**
     * Find rumah sakit by ID.
     *
     * @param int $id
     * @return TblRumahSakit
     * @throws \Exception
     */
    public function findById(int $id): TblRumahSakit
    {
        try {
            return TblRumahSakit::findOrFail($id);
        } catch (\Exception $e) {
            throw new \Exception('Data rumah sakit tidak ditemukan');
        }
    }

    /**
     * Get all rumah sakit for dropdown options.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllForDropdown()
    {
        return TblRumahSakit::orderBy('nama_rumah_sakit')->get(['id', 'nama_rumah_sakit']);
    }
}
