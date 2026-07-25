# TODO - Fix Absensi JS Fetch & Request

## Plan
1. ✅ Understand all files and identify mismatches
2. ✅ Plan approved
3. ✅ **Replace `public/js/absensi.js`** with corrected version:
   - ✅ Fix jadwal array rendering (iterates over array items with correct prop names: `nama_mapel`, `nama_kelas`, `jam_masuk`, `jam_pulang`)
   - ✅ Fix wajah status check (`wajahRes.data.terdaftar` instead of `wajahRes.is_registered`)
   - ✅ Keep correct payload structure (`descriptor`, `foto`, `latitude`, `longitude`)
   - ✅ Keep proper SubmitButtonController and busy flag logic
   - ✅ Fix `dom.infoJadwal` → `els.infoJadwal` reference
4. ✅ Done - all changes implemented

