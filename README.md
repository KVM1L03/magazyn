# Magazyn - Aplikacja do Zarządzania Magazynem

Aplikacja składa się z backendu w Symfony (PHP) i frontendu w React. Wszystko uruchamia się w Docker.

## Jak uruchomić

### Co musisz mieć zainstalowane

- Docker Desktop
- Git

### Krok 1: Pobierz kod

```bash
git clone https://github.com/KVM1L03/magazyn.git
cd magazyn
```

### Krok 2: Skopiuj konfigurację

**Root (główny katalog):**
Stwórz plik `.env` w głównym katalogu projektu:
```
DB_PASSWORD=root123
DB_NAME=magazyn
```

**Backend:**
Stwórz plik `backend/.env.dev`:
```
APP_SECRET=jakis_dlugiy_tekst_64_znaki_minimum_abcdef1234567890abcdef12
```

### Krok 3: Uruchom wszystko

```bash
docker-compose up --build
```

Poczekaj kilka minut. Docker pobierze MySQL i uruchomi wszystko. Konsola pokaże dużo tekstu - to normalne.

### Krok 4: Sprawdź czy działa

Otwórz w przeglądarce:
- http://localhost:3000 - frontend 
- http://localhost:8000 - backend API

## Przydatne komendy

### Zatrzymaj aplikację
```bash
docker-compose down
```

### Uruchom ponownie
```bash
docker-compose up
```

### Zobacz co się dzieje (logi)
```bash
docker-compose logs backend
docker-compose logs frontend
```

## Gdy coś nie działa

### Port zajęty
```bash
docker-compose down
netstat -ano | findstr :3000
netstat -ano | findstr :8000
```

### Baza danych nie startuje
```bash
docker-compose down -v
docker-compose up --build
```

## Porty

- 3000 - strona React
- 8000 - API Symfony  
- 3307 - baza MySQL