<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TblPasien extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'tbl_pasiens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_pasien',
        'id_rumah_sakit',
        'alamat',
        'no_telp',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the hospital that owns the patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rumah_sakit()
    {
        return $this->belongsTo(TblRumahSakit::class, 'id_rumah_sakit');
    }

    /**
     * Scope to search patients by name or hospital.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nama_pasien', 'like', "%{$search}%")
            ->orWhereHas('rumah_sakit', function ($q) use ($search) {
                $q->where('nama_rumah_sakit', 'like', "%{$search}%");
            });
    }

    /**
     * Scope to filter by hospital.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $hospitalId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByHospital($query, $hospitalId)
    {
        return $query->where('id_rumah_sakit', $hospitalId);
    }
}
