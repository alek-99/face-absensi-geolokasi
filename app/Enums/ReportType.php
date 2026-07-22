<?php

namespace App\Enums;

enum ReportType:string
{
    case JADWAL_GURU = 'jadwal_guru';

    case ABSENSI_HARIAN = 'absensi_harian';

    case ABSENSI_BULANAN = 'absensi_bulanan';

    case ABSENSI_TAHUNAN = 'absensi_tahunan';

    case DATA_GURU = 'data_guru';

    case DATA_JADWAL = 'data_jadwal';
}