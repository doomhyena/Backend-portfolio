# Hotel

Ez a projekt egy egyszerű hotel foglalási rendszer backendjét mutatja be. A hangsúly nem a vizuális megjelenítésen, hanem az adatmodellezésen, üzleti logikán és a backend gondolkodásmódon van.

A célom az volt, hogy egy valós problémát (szobák, foglalások, ütközések kezelése) érthető, bővíthető és strukturált módon oldjak meg natív PHP használatával.

---

## Cél

Ez a projekt **nem éles rendszer**, hanem **oktatási / portfólió célú backend**.

A célja az volt, hogy megmutassam:
**hogyan gondolkodom egy hotel foglalási rendszer backend logikájáról**,
nem csak kódolás szinten, hanem **adatmodell, üzleti szabályok és bővíthetőség** szempontjából is.

Nem „copy-paste tutorial”, hanem saját logikára épített megoldás.

---

## Fő funkciók

* Szobatípusok kezelése (ár, férőhely, leírás)
* Konkrét szobák kezelése (szobaszám, típushoz kötés)
* Foglalások kezelése

  * érkezés / távozás dátummal
  * ütközések kezelése (ne lehessen ugyanarra a szobára két foglalás)
* Vendégadatok tárolása
* Alap admin szemléletű működés (CRUD logika)
* Strukturált adatbázis-használat idegen kulcsokkal

A fókusz **nem a dizájnon**, hanem a **helyes backend működésen** van.

---

## Tech stack

* **Backend:** PHP 8.x
* **Frontend:** vanilla HTML, CSS, minimális JavaScript
* **Adatkezelés:**

  * MySQL
  * relációs adatmodell (foreign key-ek, normalizálás)

Framework nincs – **direkt natív PHP**, hogy látszódjon az alapok ismerete.

---

## Mappastruktúra (irányelv, igazítsd a sajátodhoz)

```text
Hotel/
├─ assets/
│  ├─ css/
│  └─ js/
├─ includes/
│  ├─ db.php
│  ├─ functions.php
│  └─ config.php
├─ admin/
│  ├─ rooms.php
│  ├─ room_types.php
│  └─ reservations.php
├─ public/
│  ├─ index.php
│  └─ reservation.php
└─ database/
   └─ schema.sql
```

A struktúra célja az volt, hogy **átlátható és később bővíthető** legyen
(pl. jogosultságkezelés, REST API, frontend leválasztás).

---

## Futtatás

1. PHP 8.x és MySQL szükséges
2. Adatbázis létrehozása a `schema.sql` alapján
3. `config.php`-ben az adatbázis adatok beállítása
4. Projekt futtatása lokális szerveren (XAMPP / Laragon / stb.)

Nem igényel extra build lépést vagy dependency-ket.

---

## Mit lehet vele demózni interjún / órán?

* relációs adatbázis-tervezés
* backend üzleti logika gondolkodás
* CRUD műveletek valódi példán
* PHP alapok **framework nélkül**
* hogyan választom szét a logikát és a megjelenítést
* hogyan építenék tovább egy rendszert (API, auth, frontend)

Ez egy jó alap arra, hogy elmagyarázzam:
**nem csak kódolok, hanem rendszerekben gondolkodom**.

---

## Kapcsolat

* Portfólió / site: [doomhyena.hu](https://doomhyena.hu)
* GitHub: [@doomhyena](https://github.com/doomhyena)
