# Jegyértékesítő

Mini jegyértékesítő rendszer, ahol eseményekre lehet jegyet venni/foglalni. A fókusz a backend-logikán van: események kezelése, jegykategóriák, kosár, rendelés/foglalás, basic validációk.

Ez a projekt a `Backend-portfolio` része, de önállóan is futtatható kis webappként.

---

## Cél

- PHP backend alapok gyakorlása “valódi” use case-re (jegyvásárlás)
- Esemény + jegykategória + rendelés folyamat összerakása
- Session, űrlapkezelés, alap biztonsági checkek (pl. mennyiségek, árak, inputok)

Nem éles rendszer, hanem oktatási / portfólió projekt: **így gondolkodom egy jegyértékesítő backendjéről**.

---

## Fő funkciók

- **Eseménylista**
  - elérhető események listázása (koncertek, programok, bármi)
  - alap adatok: cím, dátum/idő, helyszín, rövid leírás

- **Jegykategóriák**
  - pl. normál / VIP / kedvezményes
  - külön árak, opcionálisan külön készlet

- **Kosár**
  - jegyek hozzáadása eseményenként
  - mennyiség növelése/csökkentése
  - kosárból tétel törlése
  - végösszeg számítása

- **Foglalás / rendelés**
  - adatok bekérése űrlappal (név, email, stb.)
  - backend ellenőrzés (érvényes darabszámok, nem negatív ár, stb.)
  - visszajelzés a felhasználónak: sikeres foglalás / hibaüzenetek

> Nincs valódi fizetés, e-mailküldés vagy PDF-jegygenerálás – itt a logika a lényeg, nem a full production feature set.

---

## Tech stack

- **Backend:** PHP (8.x)
- **Frontend:** vanilla HTML, CSS, esetleg minimál JS
- **Adatkezelés:** 
  - PHP session a kosárhoz
  - esemény- és jegyadatok: egyszerű adatbázis vagy mockolt tömb(ök)

---

## Mappastruktúra (irányelv, igazítsd a sajátodhoz)

```text
jegyertekesito/
├─ assets/                          # CSS, JS, képek és ami szükséges az oldalhoz
├─ docs/                            # Itt található a dokumentáció
├─ uploads/                         # Az oldalra feltöltött rendezvényképek 
├─ 3. Jegyértékesítő oldal.docx     # Feladatleírás
├─ felhasznalo.php                  # A felhasználó profilja
├─ index.php                        # A főoldal
├─ jegyhozzadasa.php                # Jegy hozzáadás 
├─ kosar.php                        # Kosár oldal
├─ logout.php                       # Kijelentkezés logikája
├─ reglog.php                       # Bejelentkezés & regisztráció
├─ rendezveny.php                   # A rendezvény oldala
└─ rendezveny.php                   # Rendezvény létrehozása oldal           
````

---

## Futtatás

1. Klónozd a repót (vagy csak a `jegyertekesito` mappát):

   ```bash
   git clone https://github.com/doomhyena/Backend-portfolio.git
   cd Backend-portfolio/jegyertekesito
   ```

2. Tedd be egy lokális webszerver alá:

   * **XAMPP / Laragon / stb.:**

     * másold a `jegyertekesito` mappát a `htdocs` / `www` alá
   * vagy használd a beépített PHP szervert:

     ```bash
     php -S localhost:8000 -t public
     ```

3. Böngészőben nyisd meg:

   * `http://localhost/jegyertekesito`
   * vagy beépített szervernél: `http://localhost:8000`

Ha DB-t használsz:

* állítsd be a kapcsolatot egy config fájlban (pl. `src/config.php`)
* futtasd a szükséges SQL sémát (események, jegykategóriák, rendelések táblák)

---

## Mit lehet vele demózni interjún / órán?

* Esemény–jegy–kosár modell elmagyarázása
* **Session alapú kosár** működése
* Backend validációk (túl sok jegy, negatív input, stb.)
* Egyszerű, de strukturált PHP kódszervezés
* Hogyan gondolkodsz a rendelési folyamat lépéseiről (lista → kosár → összegzés → foglalás)

---

## Kapcsolat

* Portfólió / site: [doomhyena.hu](https://doomhyena.hu)
* GitHub: [@doomhyena](https://github.com/doomhyena)
