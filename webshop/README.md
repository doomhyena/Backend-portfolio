# Webshop

Kis, tanulós webshop-backend, ahol azt mutatom meg, hogyan rakok össze egy egyszerű terméklistát, kosarat és rendeléslogikát “nulláról”, full vanilla stackkel (PHP + egy kis JS + sima HTML/CSS).

Ez a projekt része a `Backend-portfolio` gyűjteménynek, de külön is futtatható, mint önálló mini-webapp.

---

## Cél

- Backend-alapok gyakorlása (routing, űrlapkezelés, session, állapotkezelés)
- Egyszerű webshop-funkciók megvalósítása frameworkök nélkül
- Tiszta, átlátható kód + logikus mappa- és fájlstruktúra

Nem production-ready cucc, hanem oktatási/portfólió projekt: “így gondolkodom backendről”.

---

## Fő funkciók

- **Terméklista**
  - statikus vagy adatbázisból töltött termékek
  - alap adatok: név, ár, leírás / kép (attól függően, mit raktam bele éppen)

- **Kosárkezelés**
  - termék hozzáadása kosárhoz
  - mennyiség növelése/csökkentése
  - termék törlése a kosárból
  - kosár összegzése (db, végösszeg)

- **Rendelés folyamata (alap)**
  - egyszerű rendelés- vagy “checkout” nézet
  - adatok ellenőrzése backend oldalon
  - visszajelzés a usernek (sikeres/sikertelen próbálkozás)

> Megjegyzés: ez egy tanulóprojekt, nincs valódi fizetés, sem production security hardening – a fókusz az alap backend logikán van.

---

## Tech stack

- **Backend:** PHP (8.x)
- **Frontend:** vanilla HTML, CSS, egy leheletnyi JavaScript
- **Adatkezelés:** PHP session + (ha be van kötve) egyszerű adatbázis / mock adatok

---

## Mappastruktúra (röviden)

```text
webshop/
├─ admin/      # az admin felület
├─ assets/     # CSS, JS, képek és ami szükséges az oldalhoz
├─ cart.php    # Kosár oldal
├─ index.php   # Főoldal
├─ login.php   # Bejelentkezés oldal
├─ order.php   # Rendelés oldal
├─ product.php # Termék oldal
└─ reg.php     # Regisztrációs oldal
````

---

## Futtatás

1. Klónozd a repót (vagy csak a `webshop` mappát):

   ```bash
   git clone https://github.com/doomhyena/Backend-portfolio.git
   cd Backend-portfolio/webshop
   ```

2. Tedd be egy lokális webszerver alá:

   * pl. **XAMPP/Laragon** esetén: másold a `webshop` mappát a `htdocs`/`www` alá
   * vagy használd a beépített PHP szervert:

     ```bash
     php -S localhost:8000 -t public
     ```

3. Böngészőben nyisd meg:

   * `http://localhost/webshop`
   * vagy ha a beépített szervert használod: `http://localhost:8000`

---

## Mit lehet vele demózni interjún / órán?

* Form adatok feldolgozása backend oldalon
* **Session alapú kosár** működésének magyarázata
* Egyszerű, de vállalható kódszervezés PHP-ben

---

## Kapcsolat

* Portfólió / site: [doomhyena.hu](https://doomhyena.hu)
* GitHub: [@doomhyena](https://github.com/doomhyena)
