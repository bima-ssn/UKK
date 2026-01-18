-- ============================================
-- DATABASE EXPORT: APLIKASI PEMINJAMAN ALAT
-- Laravel 12 + MySQL
-- UKK SMK RPL 2025/2026
-- ============================================

-- Database: peminjaman_alat
-- Charset: utf8mb4
-- Collation: utf8mb4_unicode_ci

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ============================================
-- TABEL: users
-- ============================================
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas','peminjam') NOT NULL DEFAULT 'peminjam',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABEL: kategori
-- ============================================
CREATE TABLE `kategori` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kategori_nama_kategori_unique` (`nama_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABEL: alat
-- ============================================
CREATE TABLE `alat` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kategori_id` bigint(20) UNSIGNED NOT NULL,
  `nama_alat` varchar(255) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `kondisi` enum('baik','rusak','perbaikan') NOT NULL DEFAULT 'baik',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `alat_kategori_id_foreign` (`kategori_id`),
  KEY `alat_stok_index` (`stok`),
  CONSTRAINT `alat_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABEL: peminjaman
-- ============================================
CREATE TABLE `peminjaman` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `status` enum('menunggu','disetujui','ditolak','dikembalikan') NOT NULL DEFAULT 'menunggu',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `peminjaman_user_id_foreign` (`user_id`),
  KEY `peminjaman_status_index` (`status`),
  CONSTRAINT `peminjaman_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABEL: detail_peminjaman
-- ============================================
CREATE TABLE `detail_peminjaman` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `peminjaman_id` bigint(20) UNSIGNED NOT NULL,
  `alat_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detail_peminjaman_peminjaman_id_foreign` (`peminjaman_id`),
  KEY `detail_peminjaman_alat_id_foreign` (`alat_id`),
  CONSTRAINT `detail_peminjaman_alat_id_foreign` FOREIGN KEY (`alat_id`) REFERENCES `alat` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detail_peminjaman_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABEL: pengembalian
-- ============================================
CREATE TABLE `pengembalian` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `peminjaman_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_dikembalikan` date NOT NULL,
  `denda` decimal(10,2) NOT NULL DEFAULT 0.00,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengembalian_peminjaman_id_unique` (`peminjaman_id`),
  CONSTRAINT `pengembalian_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABEL: log_aktivitas
-- ============================================
CREATE TABLE `log_aktivitas` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_aktivitas_user_id_foreign` (`user_id`),
  KEY `log_aktivitas_waktu_index` (`waktu`),
  CONSTRAINT `log_aktivitas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- FUNCTION: hitung_denda
-- ============================================
DROP FUNCTION IF EXISTS `hitung_denda`;
DELIMITER $$
CREATE FUNCTION `hitung_denda`(
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
END$$
DELIMITER ;

-- ============================================
-- PROCEDURE: proses_peminjaman
-- ============================================
DROP PROCEDURE IF EXISTS `proses_peminjaman`;
DELIMITER $$
CREATE PROCEDURE `proses_peminjaman`(
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
END$$
DELIMITER ;

-- ============================================
-- TRIGGER: after_peminjaman_approved
-- ============================================
DROP TRIGGER IF EXISTS `after_peminjaman_approved`;
DELIMITER $$
CREATE TRIGGER `after_peminjaman_approved`
AFTER UPDATE ON `peminjaman`
FOR EACH ROW
BEGIN
    IF NEW.status = 'disetujui' AND OLD.status = 'menunggu' THEN
        UPDATE alat a
        INNER JOIN detail_peminjaman dp ON a.id = dp.alat_id
        SET a.stok = a.stok - dp.jumlah
        WHERE dp.peminjaman_id = NEW.id
        AND a.stok >= dp.jumlah;
    END IF;
END$$
DELIMITER ;

-- ============================================
-- TRIGGER: after_pengembalian_insert
-- ============================================
DROP TRIGGER IF EXISTS `after_pengembalian_insert`;
DELIMITER $$
CREATE TRIGGER `after_pengembalian_insert`
AFTER INSERT ON `pengembalian`
FOR EACH ROW
BEGIN
    UPDATE alat a
    INNER JOIN detail_peminjaman dp ON a.id = dp.alat_id
    SET a.stok = a.stok + dp.jumlah
    WHERE dp.peminjaman_id = NEW.peminjaman_id;
    
    UPDATE peminjaman
    SET status = 'dikembalikan'
    WHERE id = NEW.peminjaman_id;
END$$
DELIMITER ;

COMMIT;

