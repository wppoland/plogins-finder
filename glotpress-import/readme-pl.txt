=== Elektilo - Product Finder Quiz for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, product finder, product quiz, product recommendation, guided selling
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 8.1
Stable tag: 1.1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Przyjazny kreator doboru produktu krok po kroku dla WooCommerce: pytania jednokrotnego wyboru prowadzą każdego klienta do jednej rekomendacji produktu. Bez jQuery.

== Description ==

Elektilo dodaje przyjazny kreator „pomóż mi wybrać” na dowolnej stronie za pomocą shortcode’u `[elektilo]`. Klienci odpowiadają na krótką serię pytań jednokrotnego wyboru i widzą jeden polecany produkt wraz z jego zdjęciem, ceną i przyciskiem prowadzącym prosto do strony produktu.

Elektilo jest rozwijane otwarcie (open source). Kod oraz miejsce do zgłaszania błędów i propozycji funkcji znajdziesz na [github.com/wppoland/plogins-finder](https://github.com/wppoland/plogins-finder).

Ty definiujesz pytania i opcje, a następnie przypisujesz produkt do każdej kombinacji odpowiedzi. Przycisk „Generuj kombinacje” buduje mapę na podstawie Twoich kroków, więc wystarczy, że wskażesz produkt dla każdej ścieżki.

= Documentation and links =

* **Dokumentacja**: [plogins.com/pl/plogins-finder/docs/](https://plogins.com/pl/plogins-finder/docs/)
* **Strona wtyczki**: [plogins.com/pl/plogins-finder/](https://plogins.com/pl/plogins-finder/)
* **Kod źródłowy**: [github.com/wppoland/plogins-finder](https://github.com/wppoland/plogins-finder)
* **Zgłoszenia błędów i propozycje funkcji**: [github.com/wppoland/plogins-finder/issues](https://github.com/wppoland/plogins-finder/issues)

= Built for speed and accessibility =

* **Bez jQuery** we własnym kodzie front-endu wtyczki, skrypt to czysty JavaScript, ładowany z opóźnieniem w stopce.
* **Bez przeskoków układu (CLS).** Dla każdego kroku i karty wyniku zarezerwowane jest miejsce, więc przechodzenie dalej nigdy nie przebudowuje strony. Zdjęcie rekomendacji ma priorytet dla szybkiego LCP.
* **Wynik oparty o REST.** Rekomendacja jest pobierana z lekkiego punktu końcowego REST (nie z admin-ajax), odczytywana na żywo, dzięki czemu cena i stan magazynowy są zawsze aktualne i nigdy nie są buforowane.
* **Przyjazny dla klawiatury.** Prawdziwe grupy przycisków radiowych, pasek postępu na żywo, widoczne obramowanie fokusu, przycisk Wstecz i pełna obsługa z klawiatury.
* **Można wznowić i udostępnić.** Bieżący krok i odpowiedzi są zapisywane w adresie URL oraz w pamięci karty przeglądarki, więc odświeżenie nie gubi postępu, a link z kompletem odpowiedzi otwiera się od razu na wyniku.

= Settings =

Strona ustawień (menu Elektilo, dostępna dla uprawnień WooCommerce) pozwala:

* Włączyć lub wyłączyć kreator, ustawić nagłówek, podtytuł i kolor akcentu oraz zdecydować, czy na karcie wyniku pokazywać cenę.
* Zbudować kroki: każdy krok to jedno pytanie z opcjami jednokrotnego wyboru; „wartość” opcji to krótki identyfikator (slug) używany do rozpoznania odpowiedzi.
* Przypisać wyniki: wygeneruj jeden wiersz na każdą kombinację odpowiedzi na podstawie kroków, a następnie wybierz polecany produkt dla każdej z nich, opcjonalnie z nagłówkiem, opisem i etykietą przycisku. Czytelna informacja pokazuje, ile kombinacji wciąż nie ma produktu.
* Ustawić produkt zapasowy pokazywany, gdy żadna kombinacja nie pasuje lub przypisany produkt jest niedostępny.

= Translation ready =

Wszystkie ciągi są przetłumaczalne przez domenę tekstową `elektilo`, a szablon `elektilo.pot` znajduje się w katalogu `/languages`. Usunięcie wtyczki kasuje jej opcje.

= How it works =

Kreator jest renderowany po stronie serwera (dzięki czemu można go buforować razem ze stroną), a przechodzenie między krokami odbywa się w całości po stronie przeglądarki, bez odpytywania serwera. Pobierana jest wyłącznie końcowa rekomendacja, żądaniem REST z tej samej domeny do Twojej witryny, i renderowana na świeżo, więc cena i stan magazynowy są aktualne. CSS i JavaScript są ładowane tylko na stronach zawierających shortcode `[elektilo]`.

== Installation ==

1. Wgraj wtyczkę do `/wp-content/plugins/elektilo` lub zainstaluj przez Wtyczki > Dodaj nową.
2. Włącz ją. WooCommerce musi być aktywne.
3. Wejdź w menu **Elektilo** w kokpicie, zbuduj kroki, wygeneruj kombinacje i wybierz produkt dla każdej z nich, a następnie włącz kreator.
4. Dodaj kreator na dowolnej stronie lub wpisie za pomocą shortcode’u `[elektilo]`.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Tak. Elektilo wymaga aktywnej instalacji WooCommerce.

= Does it use jQuery? =

Nie. Własny skrypt front-endu wtyczki to czysty JavaScript, bez zależności od jQuery.

= Where do I put the quiz? =

Wszędzie tam, gdzie zadziała shortcode `[elektilo]`, na stronie, we wpisie albo w bloku surowego HTML / shortcode w kreatorze stron.

= How many questions can I ask? =

Tyle kroków, ile chcesz, każdy z dowolną liczbą opcji jednokrotnego wyboru. Najlepiej konwertują dwa lub trzy krótkie kroki.

= What happens at the end? =

Klient widzi jeden polecany produkt: jego zdjęcie, opcjonalną cenę, opcjonalny nagłówek i opis oraz przycisk prowadzący do strony produktu. Jeśli żadna kombinacja nie pasuje lub przypisany produkt jest niedostępny, pokazywany jest produkt zapasowy.

= Is the price on the result always up to date? =

Tak. Rekomendacja jest pobierana na żywo przez REST przy każdym żądaniu i nigdy nie jest buforowana, więc cena i stan magazynowy odzwierciedlają stan sklepu w danej chwili.

= Does this plugin work on WordPress Multisite? =

Tak. Włącz ją dla całej sieci lub w pojedynczych witrynach; każda witryna zachowuje własne ustawienia.

== Screenshots ==

1. Kreator doboru produktu krok po kroku w sklepie.
2. Karta rekomendacji z produktem i wezwaniem do działania.
3. Ekran ustawień Elektilo: kreator kroków i mapa wyników.

== External Services ==

Elektilo nie łączy się z żadną usługą zewnętrzną ani serwerem podmiotu trzeciego i nie wysyła do nich żadnych danych. Nie dołącza żadnego SDK, klienta API, czcionki internetowej, kafelka mapy, zasobu CDN ani wywołania analitycznego, wszystko działa w Twojej własnej witrynie.

Wszystkie dane pozostają w bazie danych WordPress: pytania, opcje, mapa wyników i ustawienia są zapisane w opcji `finder_settings` (a `finder_db_version` śledzi wersję schematu). Gdy klient kończy kreator, jego odpowiedzi są wysyłane żądaniem REST z tej samej domeny do punktu końcowego `/wp-json/` Twojej witryny, który zwraca rekomendację; nigdy nie jest wykonywane wychodzące żądanie HTTP. Bieżący krok i odpowiedzi są dodatkowo zapisywane w adresie URL strony oraz w pamięci sesji karty przeglądarki, dzięki czemu kreator można wznowić. Usunięcie wtyczki kasuje jej opcje.

== Changelog ==

= 1.1.2 =
* Nazwa Elektilo. Zespół recenzentów WordPress.org wymaga, aby nazwa wtyczki zaczynała się od wyróżniającego, wymyślonego identyfikatora, a nie od ogólnego słowa opisowego. Elektilo to w esperanto narzędzie, które wybiera, czyli dokładnie to, co robi ten kreator. Domena tekstowa idzie za nazwą; zapisane dane, ustawienia i wszystkie hooki pozostają bez zmian.
* Shortcode to teraz `[elektilo]`. Wcześniej był to `[finder]`, znacznik na tyle ogólny, że mogła go zająć dowolna inna wtyczka. To ostatnie wydanie, w którym można go zmienić bez psucia istniejących stron, ponieważ wtyczka nie została jeszcze opublikowana.
* Menu w kokpicie pokazuje Elektilo zamiast Product Finder, a miejsca w dokumentacji, które wciąż mówiły Finder, zostały poprawione.

= 1.0.10 =
* Poprawka: znaki strzałek w ścieżkach menu kokpitu i w ciągach przekazywanych tłumaczom. Strzałka wewnątrz ciągu do tłumaczenia czyni z tego znaku problem każdego tłumacza i zmienia układ w każdym języku, który ją gubi.

= 1.0.9 =
* Krótki opis miał 151 znaków, o jeden za dużo wobec limitu WordPress.org, więc katalog wtyczek ucinał go w połowie zdania. Skrócony o jedno słowo; sens pozostał ten sam.

= 1.0.8 =
* Szablon tłumaczeń został wygenerowany od nowa. Nadal nosił nazwę starszej wersji wtyczki i wskazywał linie źródłowe, które od tego czasu się przesunęły, a to właśnie odczytują narzędzia tłumaczeniowe, aby pokazać ciąg w kontekście.

= 1.0.7 =
* Zmiana nazwy na Plogins Finder - Product Finder Quiz for WooCommerce, aby nazwa zaczynała się od marki, a nie od ogólnego słowa, czego wymaga zespół recenzentów wtyczek WordPress.org. Slug wtyczki pozostaje bez zmian.

= 1.0.6 =
* Przetestowano z WordPress 7.1. Zweryfikowane przez włączenie tej wersji na czystej instalacji 7.1 z WooCommerce 11.1, a nie przez edycję nagłówka.

= 1.0.5 =
* Poprawki dostępności w znacznikach kokpitu i sklepu.

= 1.0.4 =
* Poprawka: na komputerze strona nie przewija się już do kreatora przy wczytaniu (fokus używa teraz preventScroll).
* Poprawka: adres URL pozostaje czysty przy pierwszej wizycie, stan kreatora (?fa=&fs=) jest zapisywany dopiero po odpowiedzi odwiedzającego.

= 1.0.3 =
* Dodano opcję „Otwórz w nowej karcie”, dzięki której polecany produkt otwiera się w nowej karcie przeglądarki.

= 1.0.2 =
* Pierwsze stabilne wydanie: przyjazny kreator doboru produktu krok po kroku z czytelnym panelem kroków i wyników, rekomendacją na żywo opartą o REST, dostępnym front-endem w czystym JavaScripcie i zerowym przeskokiem układu. Wskaźniki opcji w stylu przycisków radiowych, subtelne animacje (z uwzględnieniem preferencji ograniczonego ruchu) i polskie tłumaczenie.
