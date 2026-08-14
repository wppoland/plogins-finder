=== Plogins Finder - Product Finder Quiz for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, product finder, product quiz, product recommendation, guided selling
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Przyjazny kreator doboru produktu krok po kroku dla WooCommerce: pytania jednokrotnego wyboru prowadzą każdego klienta do jednej rekomendacji produktu. Bez jQuery.

== Description ==

Finder dodaje przyjazny kreator „pomóż mi wybrać” na dowolnej stronie za pomocą shortcode’u `[finder]`. Klienci odpowiadają na krótką serię pytań jednokrotnego wyboru i widzą jeden polecany produkt wraz z jego zdjęciem, ceną i przyciskiem prowadzącym prosto do strony produktu.

Finder jest rozwijany otwarcie (open source). Kod oraz miejsce do zgłaszania błędów i propozycji funkcji znajdziesz na https://github.com/wppoland/plogins-finder.

Ty definiujesz pytania i opcje, a następnie przypisujesz produkt do każdej kombinacji odpowiedzi. Przycisk „Generuj kombinacje” buduje mapę na podstawie Twoich kroków, więc wystarczy, że wskażesz produkt dla każdej ścieżki.

= Documentation and links =

* <strong>Dokumentacja</strong> - https://plogins.com/pl/plogins-finder/docs/
* <strong>Strona wtyczki</strong> - https://plogins.com/pl/plogins-finder/
* <strong>Kod źródłowy</strong> - https://github.com/wppoland/plogins-finder
* <strong>Zgłoszenia błędów i propozycje funkcji</strong> - https://github.com/wppoland/plogins-finder/issues

= Built for speed and accessibility =

* <strong>Bez jQuery</strong> we własnym kodzie front-endu wtyczki, skrypt to czysty JavaScript, ładowany z opóźnieniem w stopce.
* <strong>Bez przeskoków układu (CLS).</strong> Dla każdego kroku i karty wyniku zarezerwowane jest miejsce, więc przechodzenie dalej nigdy nie przebudowuje strony. Zdjęcie rekomendacji ma priorytet dla szybkiego LCP.
* <strong>Wynik oparty o REST.</strong> Rekomendacja jest pobierana z lekkiego punktu końcowego REST (nie z admin-ajax), odczytywana na żywo, dzięki czemu cena i stan magazynowy są zawsze aktualne i nigdy nie są buforowane.
* <strong>Przyjazny dla klawiatury.</strong> Prawdziwe grupy przycisków radiowych, pasek postępu na żywo, widoczne obramowanie fokusu, przycisk Wstecz i pełna obsługa z klawiatury.
* <strong>Można wznowić i udostępnić.</strong> Bieżący krok i odpowiedzi są zapisywane w adresie URL oraz w pamięci karty przeglądarki, więc odświeżenie nie gubi postępu, a link z kompletem odpowiedzi otwiera się od razu na wyniku.

= Settings =

Strona ustawień (menu Finder, dostępna dla uprawnień WooCommerce) pozwala:

* Włączyć lub wyłączyć kreator, ustawić nagłówek, podtytuł i kolor akcentu oraz zdecydować, czy na karcie wyniku pokazywać cenę.
* Zbudować kroki: każdy krok to jedno pytanie z opcjami jednokrotnego wyboru; „wartość” opcji to krótki identyfikator (slug) używany do rozpoznania odpowiedzi.
* Przypisać wyniki: wygeneruj jeden wiersz na każdą kombinację odpowiedzi na podstawie kroków, a następnie wybierz polecany produkt dla każdej z nich, opcjonalnie z nagłówkiem, opisem i etykietą przycisku. Czytelna informacja pokazuje, ile kombinacji wciąż nie ma produktu.
* Ustawić produkt zapasowy pokazywany, gdy żadna kombinacja nie pasuje lub przypisany produkt jest niedostępny.

= Translation ready =

Wszystkie ciągi są przetłumaczalne przez domenę tekstową `plogins-finder`, a szablon `plogins-finder.pot` znajduje się w katalogu `/languages`. Usunięcie wtyczki kasuje jej opcje.

= How it works =

Kreator jest renderowany po stronie serwera (dzięki czemu można go buforować razem ze stroną), a przechodzenie między krokami odbywa się w całości po stronie przeglądarki, bez odpytywania serwera. Pobierana jest wyłącznie końcowa rekomendacja, żądaniem REST z tej samej domeny do Twojej witryny, i renderowana na świeżo, więc cena i stan magazynowy są aktualne. CSS i JavaScript są ładowane tylko na stronach zawierających shortcode `[finder]`.

== Installation ==

1. Wgraj wtyczkę do `/wp-content/plugins/finder` lub zainstaluj przez Wtyczki → Dodaj nową.
2. Włącz ją. WooCommerce musi być aktywne.
3. Wejdź w menu <strong>Finder</strong> w kokpicie, zbuduj kroki, wygeneruj kombinacje i wybierz produkt dla każdej z nich, a następnie włącz kreator.
4. Dodaj kreator na dowolnej stronie lub wpisie za pomocą shortcode’u `[finder]`.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Tak. Finder wymaga aktywnej instalacji WooCommerce.

= Does it use jQuery? =

Nie. Własny skrypt front-endu wtyczki to czysty JavaScript, bez zależności od jQuery.

= Where do I put the quiz? =

Wszędzie tam, gdzie zadziała shortcode `[finder]`, na stronie, we wpisie albo w bloku surowego HTML / shortcode w kreatorze stron.

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
3. Ekran ustawień Finder: kreator kroków i mapa wyników.

== External Services ==

Finder nie łączy się z żadną usługą zewnętrzną ani serwerem podmiotu trzeciego i nie wysyła do nich żadnych danych. Nie dołącza żadnego SDK, klienta API, czcionki internetowej, kafelka mapy, zasobu CDN ani wywołania analitycznego, wszystko działa w Twojej własnej witrynie.

Wszystkie dane pozostają w bazie danych WordPress: pytania, opcje, mapa wyników i ustawienia są zapisane w opcji `finder_settings` (a `finder_db_version` śledzi wersję schematu). Gdy klient kończy kreator, jego odpowiedzi są wysyłane żądaniem REST z tej samej domeny do punktu końcowego `/wp-json/` Twojej witryny, który zwraca rekomendację; nigdy nie jest wykonywane wychodzące żądanie HTTP. Bieżący krok i odpowiedzi są dodatkowo zapisywane w adresie URL strony oraz w pamięci sesji karty przeglądarki, dzięki czemu kreator można wznowić. Usunięcie wtyczki kasuje jej opcje.

== Changelog ==

= 1.0.2 =
* Pierwsze stabilne wydanie: przyjazny kreator doboru produktu krok po kroku z czytelnym panelem kroków i wyników, rekomendacją na żywo opartą o REST, dostępnym front-endem w czystym JavaScripcie i zerowym przeskokiem układu. Wskaźniki opcji w stylu przycisków radiowych, subtelne animacje (z uwzględnieniem preferencji ograniczonego ruchu) i polskie tłumaczenie.
