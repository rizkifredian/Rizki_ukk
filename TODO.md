# TODO List for Fixing Transaksi Keluar

## Completed Tasks

- [x] Analyze the issue: Transaksi keluar was using separate table 'transaksi_keluar' instead of unified 'transaksi' table with jenis_transaksi.
- [x] Update insert logic in transaksi_keluar.php to use 'transaksi' table with jenis_transaksi = 'keluar'.
- [x] Add total calculation (harga \* jumlah) for outgoing transactions.
- [x] Update table query to select from 'transaksi' table where jenis_transaksi = 'keluar'.
- [x] Add Total column to the table header and body in transaksi_keluar.php.

## Summary

The outgoing transaction part has been fixed to use the unified transaction table, ensuring dashboard counts are accurate and data is consistent.
