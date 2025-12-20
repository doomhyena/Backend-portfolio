CREATE TABLE `reservations` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_id` int(10) UNSIGNED NOT NULL,
  `guest_name` varchar(100) NOT NULL,
  `guest_email` varchar(150) NOT NULL,
  `checkin_date` date NOT NULL,
  `checkout_date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('lefoglalva','elfogadva','megerkezett','fizetett') NOT NULL DEFAULT 'lefoglalva'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;


INSERT INTO `reservations` (`id`, `room_id`, `guest_name`, `guest_email`, `checkin_date`, `checkout_date`, `created_at`, `status`) VALUES
(1, 1, 'Csontos Kincső', 'csontoskincso@doomhyena.hu', '2025-12-10', '2025-12-20', '2025-12-09 10:36:04', 'lefoglalva'),
(5, 3, 'Csontos Kincső', 'csontoskincso@doomhyena.hu', '2025-12-10', '2025-12-20', '2025-12-09 11:11:26', 'fizetett'),
(2, 2, 'Kovács Anna', 'anna.kovacs@gmail.com', '2025-12-05', '2025-12-08', '2025-12-01 09:15:00', 'elfogadva'),
(3, 4, 'Nagy Péter', 'peter.nagy@gmail.com', '2025-12-12', '2025-12-15', '2025-12-02 14:22:10', 'lefoglalva'),
(4, 5, 'Szabó Júlia', 'julia.szabo@gmail.com', '2025-12-18', '2025-12-22', '2025-12-03 16:45:33', 'megerkezett'),
(6, 8, 'Tóth Márton', 'marton.toth@gmail.com', '2025-12-01', '2025-12-04', '2025-11-28 10:05:11', 'fizetett'),
(7, 2, 'Kiss Dóra', 'dora.kiss@gmail.com', '2025-12-22', '2025-12-27', '2025-12-10 12:34:56', 'fizetett'),
(8, 9, 'Varga Balázs', 'balazs.varga@gmail.com', '2025-12-28', '2026-01-02', '2025-12-15 08:20:00', 'lefoglalva'),
(9, 10, 'Farkas Eszter', 'eszter.farkas@gmail.com', '2025-12-05', '2025-12-07', '2025-12-01 19:30:25', 'elfogadva'),
(10, 11, 'Horváth Levente', 'levente.horvath@gmail.com', '2025-12-09', '2025-12-11', '2025-12-06 11:11:11', 'fizetett');


-- A room_number a floor és room_index alapján lesz kiszámolva (pl. 1*100 + 1 = 101)
CREATE TABLE `rooms` (
  `id` int(10) UNSIGNED NOT NULL,
  `floor` tinyint(3) UNSIGNED NOT NULL,
  `room_index` tinyint(3) UNSIGNED NOT NULL,
  `room_number` int(11) GENERATED ALWAYS AS (`floor` * 100 + `room_index`) STORED,
  `room_type_id` int(10) UNSIGNED NOT NULL,
  `price_per_night` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
);

INSERT INTO `rooms` (`id`, `floor`, `room_index`, `room_type_id`, `price_per_night`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 8000, 1, '2025-12-02 09:45:59', '2025-12-02 09:45:59'),
(2, 1, 2, 2, 15000, 1, '2025-12-02 09:45:59', '2025-12-04 09:01:11'),
(3, 1, 3, 2, 11000, 1, '2025-12-02 09:45:59', '2025-12-02 09:45:59'),
(4, 2, 1, 2, 12000, 1, '2025-12-02 09:45:59', '2025-12-02 09:45:59'),
(5, 2, 2, 3, 14000, 1, '2025-12-02 09:45:59', '2025-12-02 09:45:59'),
(7, 3, 1, 6, 45000, 0, '2025-12-04 09:50:22', '2025-12-04 09:50:22'),
(8, 3, 2, 5, 30000, 1, '2025-12-04 09:51:57', '2025-12-04 09:51:57'),
(9, 2, 3, 4, 16000, 1, '2025-12-05 10:00:00', '2025-12-05 10:00:00'),
(10, 2, 4, 4, 17000, 1, '2025-12-05 10:05:00', '2025-12-05 10:05:00'),
(11, 3, 3, 6, 38000, 1, '2025-12-06 11:15:00', '2025-12-06 11:15:00'),
(12, 4, 1, 4, 18000, 1, '2025-12-06 12:00:00', '2025-12-06 12:00:00'),
(13, 4, 2, 5, 32000, 1, '2025-12-06 12:10:00', '2025-12-06 12:10:00'),
(14, 4, 3, 6, 50000, 0, '2025-12-06 12:20:00', '2025-12-06 12:20:00');


CREATE TABLE `room_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `capacity` tinyint(3) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

INSERT INTO `room_types` (`id`, `name`, `capacity`, `description`, `created_at`, `updated_at`) VALUES
(1, '1 fős', 1, 'Egyágyas szoba', '2025-12-02 09:45:59', '2025-12-02 09:45:59'),
(2, '2 fős', 2, 'Kétágyas szoba', '2025-12-02 09:45:59', '2025-12-04 09:01:11'),
(3, '3 fős', 3, 'Háromfős szoba', '2025-12-02 09:45:59', '2025-12-02 09:45:59'),
(4, '4 fős', 4, 'Négyfős szoba', '2025-12-02 09:45:59', '2025-12-02 09:45:59'),
(5, 'Lakosztály 1 háló', 2, 'Lakosztály: 1 hálószoba, nappali', '2025-12-02 09:45:59', '2025-12-02 09:45:59'),
(6, 'Lakosztály 2 háló', 4, 'Lakosztály: 2 hálószoba, nappali', '2025-12-02 09:45:59', '2025-12-02 09:45:59'),

-- ÚJ szobatípusok
(7, 'Családi szoba', 5, 'Tágas családi szoba gyerekekkel érkezőknek.', '2025-12-05 09:00:00', '2025-12-05 09:00:00'),
(8, 'Business szoba', 1, 'Business utazóknak kialakított, íróasztallal és gyors wifivel.', '2025-12-05 09:05:00', '2025-12-05 09:05:00');


ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reservation_room` (`room_id`),
  ADD KEY `idx_res_status_checkin` (`status`,`checkin_date`);

ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_room_number` (`room_number`),
  ADD KEY `idx_rooms_floor` (`floor`),
  ADD KEY `idx_rooms_room_type` (`room_type_id`);

ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `reservations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `rooms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `room_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_reservation_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `rooms`
  ADD CONSTRAINT `fk_room_type` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON UPDATE CASCADE;
COMMIT;
