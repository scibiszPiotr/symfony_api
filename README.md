## Task:
REST API Utwórz REST API przy użyciu frameworka Laravel / Symfony. Celem aplikacji jest umożliwienie przesłania przez użytkownika informacji odnośnie firmy(nazwa, NIP, adres, miasto, kod pocztowy) oraz jej pracowników(imię, nazwisko, email, numer telefonu(opcjonalne)) - wszystkie pola są obowiązkowe poza tym które jest oznaczone jako opcjonalne. Uzupełnij endpointy do pełnego CRUDa dla powyższych dwóch. Zapisz dane w bazie danych. PS. Stosuj znane Ci dobre praktyki wytwarzania oprogramowania oraz korzystaj z repozytorium kodu.

## UnitTests
`./vendor/bin/phpunit`

## Instalation
* `docker compose up -d` -> db in mysql 
* `composer install`
* ` php bin/console d:m:m`
* `symfony server:start`

## Endpoint
* `/api/companies` GET
* `/api/companies` POST
* `/api/companies/{id}` GET
* `/api/companies/{id}` PUT
* `/api/companies/{id}` DELETE
* `/api/employees` GET
* `/api/employees` POST
* `/api/employees/{id}` GET
* `/api/employees/{id}` PUT
* `/api/employees/{id}` DELETE

### Body for POST
`{
    "firstName": "Jon",
    "lastName": "Test",
    "email": "test@test.pl",
    "phone": "656 676 676",
    "companyId": 1
}`

`{
    "name": "Company",
    "nip": 1234567890,
    "address": "ul Gdańska 57",
    "city": "Gdynia",
    "postalCode": "99-876"
}`
