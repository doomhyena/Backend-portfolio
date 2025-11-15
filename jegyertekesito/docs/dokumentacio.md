# Jegyértékesítő Webalkalmazás – Dokumentáció

## Projekt áttekintés
Ez a webalkalmazás egy online koncert- és rendezvényjegy értékesítő rendszer, ahol a felhasználók eseményeket böngészhetnek, jegyeket vásárolhatnak, saját rendezvényt hozhatnak létre, és kezelhetik a saját eseményeikhez tartozó jegyeket.

## Fő funkciók
- **Regisztráció és bejelentkezés**: Felhasználók regisztrálhatnak, majd bejelentkezhetnek a rendszerbe.
- **Rendezvények böngészése**: A főoldalon minden elérhető rendezvény listázva van, képpel, dátummal, részletekkel.
- **Rendezvény részletei**: Egy rendezvény oldalán láthatók a részletes adatok és az elérhető jegyek.
- **Jegyvásárlás**: A felhasználó kiválaszthatja, hány jegyet szeretne venni, és kosárba teheti őket. A vásárlás után a jegyek készlete automatikusan csökken.
- **Kosár**: A kosárban a felhasználó áttekintheti a kiválasztott jegyeket, véglegesítheti a vásárlást.
- **Saját rendezvények kezelése**: A profil oldalon a felhasználó láthatja a saját rendezvényeit, és új jegyeket adhat hozzá.
- **Rendezvény létrehozása**: Új esemény létrehozása képfeltöltéssel, leírással, dátummal.
- **Kijelentkezés**: A felhasználó bármikor kijelentkezhet.

## Főbb fájlok és felépítés
- `index.php`: Főoldal, rendezvények listázása.
- `rendezveny.php`: Rendezvény részletei, jegyvásárlás (darabszám megadásával, készletellenőrzéssel).
- `kosar.php`: Kosár tartalma, vásárlás véglegesítése, készlet frissítése.
- `felhasznalo.php`: Profil oldal, saját rendezvények és jegyek kezelése.
- `jegyhozzadasa.php`: Jegyek hozzáadása egy adott rendezvényhez.
- `rendezvény_letrehozasa.php`: Új rendezvény létrehozása.
- `reglog.php`: Regisztráció és bejelentkezés.
- `assets/php/navbar.php`, `footer.php`: Navigációs sáv és lábléc.
- `assets/css/styles.css`: Modern, reszponzív, egységes dizájn.
- `assets/sql/db.sql`: Adatbázis szerkezet (táblák: felhasznalok, rendezvenyek, jegyek).

## Működés röviden
- A felhasználó regisztrál, majd bejelentkezik.
- A főoldalon böngészhet a rendezvények között.
- Egy rendezvény oldalán kiválaszthatja, hány jegyet szeretne venni (csak a készlet erejéig).
- A kosárban véglegesítheti a vásárlást, ekkor a jegyek készlete csökken.
- A profil oldalon láthatja saját rendezvényeit, és új jegyeket adhat hozzá.
- Új rendezvényt is létrehozhat, képfeltöltéssel.

## Technológiák
- **Frontend**
    - HTML5
    - CSS3
    - JavaScript
- **Backend**:
    - PHP
- **Adatbázis**: 
    - MySQL 
- **Szoftverek**:
    - XAMPP (Apache, MySQL, PHP)
    - Visual Studio Code
    - Windows 11
- **Hardverek**:
    - Videókártya (GPU): NVIDIA RTX 3050 6gb Laptop GPU
    - Processzor (CPU): AMD Ryzen 5 7235HS
    - RAM: 16GB DDR5 4800 MHz


## Fontosabb biztonsági és működési jellemzők
- Minden oldal csak bejelentkezett felhasználó számára elérhető (kivéve regisztráció/bejelentkezés).
- Jegyvásárlásnál és kosárba rakásnál készletellenőrzés.
- Egy jegyből egyszerre több is vásárolható, de csak a készlet erejéig.
- Elfogyott jegy esetén a vásárlás nem engedélyezett, és jól látható üzenet jelenik meg.
- Rendezvény létrehozásakor képfeltöltés, automatikus mappakezelés.

## Fejlesztői információk
- A kód jól tagolt, minden fő funkció külön fájlban található.
- A dizájn egységes, minden oldal reszponzív.
- A kosár cookie-ban tárolódik, így a felhasználó böngészőjében marad a vásárlás véglegesítéséig.
- A rendszer könnyen bővíthető további funkciókkal (pl. jegy PDF generálás, e-mail értesítés, admin felület).

## Telepítés és használat
1. Másold a projektet a XAMPP `htdocs` mappájába.
2. Importáld az `assets/sql/db.sql` fájlt a phpMyAdmin-ban.
3. Indítsd el az Apache és MySQL szervereket.
4. Nyisd meg a böngészőben: `http://localhost/jegyertekesito/`
5. Regisztrálj, majd jelentkezz be, és már használhatod is a rendszert.

## Az oldal részletes működése (felhasználói szemszögből)

### 1. Regisztráció és bejelentkezés
- A felhasználó a főoldalról vagy a navigációs sávból elérheti a regisztrációs és bejelentkezési űrlapot.
- Sikeres regisztráció után automatikusan bejelentkezik, vagy külön bejelentkezhet.
- A rendszer session/cookie segítségével kezeli a bejelentkezett állapotot.

### 2. Rendezvények böngészése
- A főoldalon (index.php) minden elérhető rendezvény megjelenik, képpel, dátummal, rövid leírással.
- A felhasználó rákattinthat egy rendezvényre, hogy megtekinthesse a részletes adatlapját.

### 3. Rendezvény részletei és jegyvásárlás
- A rendezvény oldalán (rendezveny.php) látható a teljes leírás, a rendezvény képe, dátuma, helyszíne, valamint az elérhető jegyek típusa és ára.
- A felhasználó kiválaszthatja, hány darab jegyet szeretne vásárolni az adott típusból (csak a készlet erejéig).
- Ha a kívánt mennyiség meghaladja a készletet, hibaüzenet jelenik meg.
- A kiválasztott jegyek a kosárba kerülnek (cookie-ban tárolva).

### 4. Kosár és vásárlás véglegesítése
- A kosár oldalon (kosar.php) a felhasználó áttekintheti a kosár tartalmát: rendezvény, jegytípus, darabszám, ár.
- Itt törölhet jegyet a kosárból, vagy véglegesítheti a vásárlást.
- Vásárláskor a rendszer újra ellenőrzi a készletet, majd csökkenti a jegyek számát az adatbázisban.
- Sikeres vásárlás után a kosár kiürül, és visszajelzés jelenik meg.

### 5. Saját profil és rendezvények kezelése
- A profil oldalon (felhasznalo.php) a felhasználó láthatja a saját rendezvényeit, valamint az ezekhez tartozó jegyeket.
- Itt lehetőség van új jegyek hozzáadására meglévő rendezvényhez (jegyhozzadasa.php).

### 6. Új rendezvény létrehozása
- A felhasználó a profil oldalról vagy a menüből elérheti az új rendezvény létrehozása oldalt (rendezvény_letrehozasa.php).
- Itt megadhatja a rendezvény nevét, leírását, dátumát, helyszínét, valamint képet tölthet fel.
- Sikeres mentés után a rendezvény megjelenik a főoldalon is.

### 7. Kijelentkezés
- A felhasználó bármikor kijelentkezhet a navigációs sávból.
- Kijelentkezés után a session/cookie törlődik, a védett oldalak nem elérhetők.

---

Ha kérdésed van vagy hibát találsz, keresd a fejlesztőt: Csontos Kincső ([GitHub](https://github.com/doomhyena))
