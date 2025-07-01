<?php

namespace App\Models;

use App\Models\EnergiListrik;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergiListrik extends Model
{
    use HasFactory;
    protected $table = 'energi_listrik';
    protected $fillable = [
        'waktu',
        'tegangan_r',
        'tegangan_s',
        'tegangan_t',
        'arus_r',
        'arus_s',
        'arus_t',
        'daya_r',
        'daya_s',
        'daya_t',
        'energi_r',
        'energi_s',
        'energi_t',
        'faktor_daya_r',
        'faktor_daya_s',
        'faktor_daya_t',
        'frekuensi_r',
        'frekuensi_s',
        'frekuensi_t',
        'voltage_rs',
        'voltage_st',
        'voltage_tr',
    ];
}
