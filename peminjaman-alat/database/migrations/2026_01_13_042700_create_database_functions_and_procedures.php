<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Function untuk menghitung denda
        DB::unprepared("
            DROP FUNCTION IF EXISTS hitung_denda;
            CREATE FUNCTION hitung_denda(
                tanggal_kembali DATE,
                tanggal_dikembalikan DATE
            ) RETURNS DECIMAL(10,2)
            DETERMINISTIC
            BEGIN
                DECLARE hari_terlambat INT;
                DECLARE denda DECIMAL(10,2);
                
                IF tanggal_dikembalikan > tanggal_kembali THEN
                    SET hari_terlambat = DATEDIFF(tanggal_dikembalikan, tanggal_kembali);
                    SET denda = hari_terlambat * 10000;
                ELSE
                    SET denda = 0;
                END IF;
                
                RETURN denda;
            END;
        ");

        // Stored Procedure untuk proses peminjaman
        DB::unprepared("
            DROP PROCEDURE IF EXISTS proses_peminjaman;
            CREATE PROCEDURE proses_peminjaman(
                IN p_user_id BIGINT UNSIGNED,
                IN p_tanggal_pinjam DATE,
                IN p_tanggal_kembali DATE,
                IN p_keterangan TEXT,
                OUT p_peminjaman_id BIGINT UNSIGNED,
                OUT p_status VARCHAR(50)
            )
            BEGIN
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    SET p_status = 'ERROR';
                    RESIGNAL;
                END;
                
                START TRANSACTION;
                
                INSERT INTO peminjaman (user_id, tanggal_pinjam, tanggal_kembali, status, keterangan, created_at, updated_at)
                VALUES (p_user_id, p_tanggal_pinjam, p_tanggal_kembali, 'menunggu', p_keterangan, NOW(), NOW());
                
                SET p_peminjaman_id = LAST_INSERT_ID();
                SET p_status = 'SUCCESS';
                
                COMMIT;
            END;
        ");

        // Trigger untuk update stok saat peminjaman disetujui
        DB::unprepared("
            DROP TRIGGER IF EXISTS after_peminjaman_approved;
            CREATE TRIGGER after_peminjaman_approved
            AFTER UPDATE ON peminjaman
            FOR EACH ROW
            BEGIN
                IF NEW.status = 'disetujui' AND OLD.status = 'menunggu' THEN
                    UPDATE alat a
                    INNER JOIN detail_peminjaman dp ON a.id = dp.alat_id
                    SET a.stok = a.stok - dp.jumlah
                    WHERE dp.peminjaman_id = NEW.id
                    AND a.stok >= dp.jumlah;
                END IF;
            END;
        ");

        // Trigger untuk update stok saat pengembalian
        DB::unprepared("
            DROP TRIGGER IF EXISTS after_pengembalian_insert;
            CREATE TRIGGER after_pengembalian_insert
            AFTER INSERT ON pengembalian
            FOR EACH ROW
            BEGIN
                UPDATE alat a
                INNER JOIN detail_peminjaman dp ON a.id = dp.alat_id
                SET a.stok = a.stok + dp.jumlah
                WHERE dp.peminjaman_id = NEW.peminjaman_id;
                
                UPDATE peminjaman
                SET status = 'dikembalikan'
                WHERE id = NEW.peminjaman_id;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS hitung_denda");
        DB::unprepared("DROP PROCEDURE IF EXISTS proses_peminjaman");
        DB::unprepared("DROP TRIGGER IF EXISTS after_peminjaman_approved");
        DB::unprepared("DROP TRIGGER IF EXISTS after_pengembalian_insert");
    }
};

