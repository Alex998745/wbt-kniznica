# Zadanie: Implementácia use casov knižničného systému v PHP

## Cieľ
Implementovať jednoduchý knižničný systém podľa priložených use casov. Pripravený HTML/CSS prototyp slúži ako návrh obrazoviek.

## Technológie
- PHP
- MySQL alebo MariaDB
- HTML
- CSS
- bez povinnosti používať framework

## Rozdelenie na 3 časti

### 1. Správa používateľov
Use cases:
- UC-00 Prihlásiť sa do systému
- UC-01 Registrovať používateľa
- UC-03 Zablokovať používateľa
- UC-03a Odblokovať používateľa
- UC-04 Zobraziť aktivity používateľa
- UC-05 Vyhľadať používateľa
- UC-06 Nastaviť notifikácie používateľa
- UC-07 Resetovať heslo

### 2. Evidencia kníh
Use cases:
- UC-10 Evidovať knihu
- UC-12 Evidovať exemplár knihy
- UC-13 Odstrániť knihu z evidencie
- UC-15 Evidovať fyzické umiestnenie knihy
- UC-16 Nahrať obálku knihy

### 3. Požičiavanie a rezervácie
Use cases:
- UC-20 Vypožičať knihu
- UC-21 Vrátiť knihu
- UC-30 Rezervovať knihu

## Minimálne požiadavky
1. Vytvoriť databázové tabuľky podľa potreby.
2. Pripojiť formuláre na PHP skripty.
3. Ukladať údaje do databázy.
4. Zobrazovať zoznamy používateľov, kníh, exemplárov, výpožičiek a rezervácií.
5. Validovať povinné polia.
6. Rozlišovať roly používateľov: Administrátor, Knihovník, Čitateľ.
7. Nepoužívať heslá v otvorenom texte – použiť password_hash().
8. Pri zmene dôležitých údajov zapisovať aktivitu používateľa.

## Odporúčaná štruktúra PHP projektu
- index.php
- login.php
- logout.php
- config/db.php
- users.php
- user_create.php
- books.php
- book_create.php
- copies.php
- loans.php
- reservations.php
- assets/style.css

## Odovzdanie
Študent odovzdá:
- zdrojové súbory PHP/HTML/CSS,
- SQL export databázy,
- stručný popis implementovaných use casov.
