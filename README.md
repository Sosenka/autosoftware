Aby uruchomić projekt wykonaj polecenia:


1. sklonuj projekt poleceniem 
```
git clone git@github.com:Sosenka/autosoftware.git .
```
2. zbuduj obraz dockera
```
docker compose build
```
3. uruchom kontenery

```
docker compose up 
```
4. przejdź do kontenera poleceniem
```
docker compose exec web sh
```
5. zainstaluj paczki composera
```angular2html
composer install
```

 # Routing

```
POST /api/v1/message

{
    "message": "teskt"
}

GET /api/v1/message

{
    "uuid": "teskt"
}

```