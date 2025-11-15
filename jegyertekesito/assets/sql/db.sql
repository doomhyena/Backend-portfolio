CREATE DATABASE IF NOT EXISTS `portfolio_jegy` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci;
USE `portfolio_jegy`;

--
-- Tábla szerkezet ehhez a táblához `eladott_jegyek`
--

CREATE TABLE `eladott_jegyek` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `ticket_count` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `jegy_id` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalok`
--

CREATE TABLE `felhasznalok` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `jelszo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `felhasznalok`
--

INSERT INTO `felhasznalok` (`id`, `username`, `email`, `jelszo`) VALUES
(1, 'csontoskincso05', 'csontoskincso05@gmail.com', '$2y$10$is/72pKOkBaxPLskG/m5PuPCtWZ6oD5TcgH3F.fg2IXT/IVQ0h/K2'),
(2, 'doomhyena', 'doomhyenaofficial@gmail.com', '$2y$10$9WMoqjk4i4VFbJWQqqBgqexcARrvKOa1Piw56rQqPSPHKuUSW8EPW');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jegyek`
--

CREATE TABLE `jegyek` (
  `id` int(11) NOT NULL,
  `nev` varchar(255) NOT NULL,
  `darabszam` int(11) NOT NULL,
  `rendezveny_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `jegyek`
--

INSERT INTO `jegyek` (`id`, `nev`, `darabszam`, `rendezveny_id`) VALUES
(1, 'VIP', 0, 1),
(3, 'Early Bird', 0, 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `rendezvenyek`
--

CREATE TABLE `rendezvenyek` (
  `id` int(11) NOT NULL,
  `nev` varchar(255) NOT NULL,
  `kep` varchar(255) NOT NULL,
  `datum` datetime NOT NULL,
  `leiras` text NOT NULL,
  `szervezo_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `rendezvenyek`
--

INSERT INTO `rendezvenyek` (`id`, `nev`, `kep`, `datum`, `leiras`, `szervezo_id`) VALUES
(1, 'Kréta Bugfix', 'peakpx.jpg', '5000-10-01 15:36:00', 'Kréta teljes bugfixelése', 1),
(2, 'Sziget Fesztivál', 'SZ25-SZIGET-LOGO-PRESALE-26-16x9-EN-1.jpg', '2026-07-05 16:00:00', '„Az Óbudai-szigetre vezető régi vasúti K-híd a Dunán 30 éve minden augusztusban hat napon át egy másik világba vezető kapuvá válik. Ahol sci-fi fényfolyosók világítják meg a drag queenekkel és éjjeli réverekkel teli arénákat. Ahol a világ legnagyobb cirkuszi előadóművészei, táncosai, világklasszis együttesei és színházi kiválóságai találkoznak, ahol a legnagyszerűbb sztárok és a legizgalmasabb feltörekvő előadók ámulatba ejtik a közönséget…\r\nEz a Sziget, ahol minden előítéletet és maradiságot hátrahagyhatsz 500.000 Szitizennel a világ minden tájáról karöltve a szárazföldön. A Sziget az a hely, ahol szabadon, minden nap megélhetjük személyiségünk egy-egy újabb oldalát..”', 2);

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `eladott_jegyek`
--
ALTER TABLE `eladott_jegyek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jegy_id` (`jegy_id`),
  ADD KEY `felhasznalo_id` (`felhasznalo_id`);

--
-- A tábla indexei `felhasznalok`
--
ALTER TABLE `felhasznalok`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `jegyek`
--
ALTER TABLE `jegyek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rendezveny_id` (`rendezveny_id`);

--
-- A tábla indexei `rendezvenyek`
--
ALTER TABLE `rendezvenyek`
  ADD PRIMARY KEY (`id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `eladott_jegyek`
--
ALTER TABLE `eladott_jegyek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `felhasznalok`
--
ALTER TABLE `felhasznalok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `jegyek`
--
ALTER TABLE `jegyek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `rendezvenyek`
--
ALTER TABLE `rendezvenyek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `eladott_jegyek`
--
ALTER TABLE `eladott_jegyek`
  ADD CONSTRAINT `eladott_jegyek_ibfk_1` FOREIGN KEY (`jegy_id`) REFERENCES `jegyek` (`id`),
  ADD CONSTRAINT `eladott_jegyek_ibfk_2` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalok` (`id`);

--
-- Megkötések a táblához `jegyek`
--
ALTER TABLE `jegyek`
  ADD CONSTRAINT `jegyek_ibfk_1` FOREIGN KEY (`rendezveny_id`) REFERENCES `rendezvenyek` (`id`);
COMMIT;
