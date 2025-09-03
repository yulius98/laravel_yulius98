<?php

namespace App\Services;

use App\Models\TblPasien;
use App\Models\TblRumahSakit;
use Illuminate\Pagination\LengthAwarePaginator;

class PasienService
{
    /**
     * Get paginated pasien data with hospital information.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedData(int $perPage = 10): LengthAwarePaginator
    {
        return TblPasien::with('rumah_sakit')
            ->orderBy('nama_pasien')
            ->paginate($perPage);
    }

    /**
     * Search pasien by query and hospital filter.
     *
     * @param string|null $query
     * @param int|null $rumahSakitId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchData(?string $query, ?int $rumahSakitId = null)
    {
        return TblPasien::with('rumah_sakit')
            ->when($query, function ($q) use ($query) {
                $q->search($query);
            })
            ->when($rumahSakitId, function ($q) use ($rumahSakitId) {
                $q->byHospital($rumahSakitId);
            })
            ->orderBy('nama_pasien')
            ->get();
    }

    /**
     * Create a new pasien.
     *
     * @param array $data
     * @return TblPasien
     * @throws \Exception
     */
    public function create(array $data): TblPasien
    {
        try {
            $pasien = TblPasien::create($data);
            $pasien->load('rumah_sakit');
            return $pasien;
        } catch (\Exception $e) {
            throw new \Exception('Gagal menyimpan data pasien: ' . $e->getMessage());
        }
    }

    /**
     * Update pasien data.
     *
     * @param int $id
     * @param array $data
     * @return TblPasien
     * @throws \Exception
     */
    public function update(int $id, array $data): TblPasien
    {
        try {
            $pasien = TblPasien::findOrFail($id);
            $pasien->update($data);
            $pasien->load('rumah_sakit');
            return $pasien;
        } catch (\Exception $e) {
            throw new \Exception('Gagal mengupdate data pasien: ' . $e->getMessage());
        }
    }

    /**
     * Delete pasien.
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id): bool
    {
        try {
            $pasien = TblPasien::findOrFail($id);
            return $pasien->delete();
        } catch (\Exception $e) {
            throw new \Exception('Gagal menghapus data pasien: ' . $e->getMessage());
        }
    }

    /**
     * Find pasien by ID with hospital information.
     *
     * @param int $id
     * @return TblPasien
     * @throws \Exception
     */
    public function findById(int $id): TblPasien
    {
        try {
            return TblPasien::with('rumah_sakit')->findOrFail($id);
        } catch (\Exception $e) {
            throw new \Exception('Data pasien tidak ditemukan');
        }
    }

    /**
     * Get all hospitals for dropdown.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllHospitals()
    {
        return TblRumahSakit::orderBy('nama_rumah_sakit')->get(['id', 'nama_rumah_sakit']);
    }
}
