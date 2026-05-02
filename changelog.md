# Changelog

Az összes jelentős változtatás ebben a fájlban kerül dokumentálásra.

## [1.0.0] - 2026-04-18

### Hozzáadva
- **Felhasználókezelés**: Regisztráció, bejelentkezés és kijelentkezés, jelszavak biztonságos tárolásával (`password_hash`) és session alapú hitelesítéssel.
- **Adatbázis**: `users` és `counters` táblák megtervezése, MySQL adatbázis kapcsolat inicializáló szkript (`init_db.php`).
- **Dashboard felület**: Főoldal (`index.php`) Bootstrap 5.3 alapú horizontális navigációs menüvel (Navbar) a számlálók kezelésére.
- **API Végpont**: `api/counters.php` REST-szerű végpont a CRUD (létrehozás, olvasás, frissítés, törlés) műveletek kiszolgálásához.
- **AJAX Integráció**: Aszinkron `fetch()` hívások (`assets/js/script.js`), így az oldal újratöltése nélkül lehet számlálókat felvenni, módosítani vagy törölni.
- **Dinamikus Témák**: Sötét (Dark), Világos (Light) és Neumann János Egyetem (NJE) témák támogatása, a választást a böngésző a `localStorage`-ben menti.

### Javítva
- **UI Hiba (Bejelentkezés)**: A "Bejelentkezés" és a "Regisztráció" paneleknél (`.card`) láthatatlan volt a szöveg a Bootstrap alapértelmezett kártya beállításai miatt. Javítva `color: var(--text-color);` hozzáadásával a CSS-ben.
- **Visszaszámláló Ugrálás**: Javítva a JavaScript időzítő (`setInterval`) pontatlanságából eredő hiba, amely miatt a másodpercek esetenként ugrottak a megjelenítésnél. A megoldás a teljes másodperc alapú kerekítés (`Math.round`) alkalmazása lett.
