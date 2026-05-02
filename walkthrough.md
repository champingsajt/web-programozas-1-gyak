# Procrastinator Exciter - Elkészült Alkalmazás

Az alkalmazás elkészült a specifikációk és a korábban elfogadott megvalósítási terv alapján.

## Megvalósított funkciók

### 1. Adatbázis és Konfiguráció
- Létrejött az `init_db.php` szkript, ami automatikusan létrehozza a `procrastinator_db` adatbázist és a szükséges `users`, illetve `counters` táblákat.
- A `config.php` tartalmazza a PDO adatbázis kapcsolatot és elindítja a munkafolyamatot (Session).

### 2. Felhasználókezelés
- **Regisztráció** (`register.php`): Jelszavak biztonságos tárolása a `password_hash()` függvény segítségével történik.
- **Bejelentkezés** (`login.php`): Munkamenet (Session) alapú hitelesítés `password_verify()` használatával.
- **Kijelentkezés** (`logout.php`): Munkamenet megsemmisítése és átirányítás.

### 3. Felhasználói Felület (Frontend)
- Beépítésre került a **Bootstrap 5.3 Navbar**, rajta egy téma-váltó legördülő menüvel.
- Megvalósításra kerültek a témák (`assets/css/style.css`):
  - **Sötét (Dark)**: Az alapértelmezett, az example mappában lévőhöz hasonló kinézet.
  - **Világos (Light)**: Világos letisztult dizájn.
  - **NJE**: Neumann János Egyetem hivatalos kék és sárga/arany színeire épülő téma.
- A témaválasztást a böngésző a `localStorage`-ben tárolja el.

### 4. CRUD Műveletek & AJAX
- Létrejött az `api/counters.php`, ami egy REST-szerű végpont a számlálók kezelésére (GET, POST, PUT, DELETE). Csak a bejelentkezett felhasználó saját adatait kezeli.
- Az `assets/js/script.js`-ben megvalósítottam a `fetch()` API hívásokat, így a felület:
  - **Kiolvas**: Betölti a számlálókat oldalfrissítés nélkül.
  - **Hozzáad / Módosít**: Bootstrap Modal használatával aszinkron módon ment, majd újraolvassa a listát.
  - **Töröl**: Megerősítő ablak után aszinkron törlés.

## Használati útmutató (Teszteléshez)

1. Győződj meg róla, hogy fut a helyi XAMPP/WAMP MySQL és Apache szerver.
2. Nyisd meg az `init_db.php` fájlt a böngésződben (pl. `http://localhost/.../init_db.php`). Ez létrehozza az adatbázist. **Fontos**: A sikeres futás után törölheted ezt a fájlt.
3. Menj a `register.php` vagy az `index.php` (ami átirányít a loginra) oldalra, hozz létre egy felhasználót.
4. Teszteld a számlálók felvételét, módosítását, törlését és a téma váltást a jobb felső sarokban!

> [!TIP]
> Az elkészült fájlstruktúra tiszta, a JS és CSS külön fájlokba (`assets/` mappa) kerültek a fenntarthatóság érdekében. SQL Injection és XSS ellen felkészített (Prepared statements a DB hívásoknál, és `htmlspecialchars()` / JS escape a HTML kiírásoknál).
