=== Plogins Finder - Product Finder Quiz for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, product finder, product quiz, product recommendation, guided selling
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Ein freundliches Schritt-für-Schritt-Produktfinder-Quiz für WooCommerce: geführte Fragen mit Einfachauswahl führen jede Kundin und jeden Kunden zu einer Produktempfehlung. Ohne jQuery.

== Description ==

Finder fügt mit dem Shortcode `[finder]` auf jeder Seite ein geführtes „Hilf mir bei der Auswahl“-Quiz hinzu. Deine Kundschaft beantwortet eine kurze Reihe von Fragen mit Einfachauswahl und sieht anschließend ein empfohlenes Produkt, mit Bild, Preis und einem Button direkt zur Produktseite.

Finder wird quelloffen entwickelt. Den Code sowie eine Stelle, um Fehler zu melden oder Funktionen vorzuschlagen, findest du unter https://github.com/wppoland/plogins-finder.

Du legst die Fragen und Optionen fest und ordnest dann jeder Antwortkombination ein Produkt zu. Der Button „Kombinationen generieren“ baut die Zuordnung aus deinen Schritten auf, sodass du nur noch für jeden Pfad ein Produkt auswählst.

= Documentation and links =

* <strong>Dokumentation</strong> - https://plogins.com/de/plogins-finder/docs/
* <strong>Plugin-Seite</strong> - https://plogins.com/de/plogins-finder/
* <strong>Quellcode</strong> - https://github.com/wppoland/plogins-finder
* <strong>Fehlerberichte und Funktionswünsche</strong> - https://github.com/wppoland/plogins-finder/issues

= Built for speed and accessibility =

* <strong>Ohne jQuery</strong> im eigenen Frontend-Code des Plugins, das Skript ist reines JavaScript, verzögert und im Footer geladen.
* <strong>Ohne Layout-Verschiebung (CLS).</strong> Für jeden Schritt und die Ergebniskarte ist Platz reserviert, sodass das Weiterklicken die Seite nie neu umbricht. Das Empfehlungsbild wird für ein schnelles LCP priorisiert.
* <strong>Ergebnis per REST.</strong> Die Empfehlung wird von einem schlanken REST-Endpunkt (nicht admin-ajax) abgerufen, live gelesen, sodass Preis und Lagerbestand immer aktuell sind, und nie zwischengespeichert.
* <strong>Tastaturfreundlich.</strong> Echte Radio-Gruppen, eine Live-Fortschrittsanzeige, sichtbare Fokus-Stile, ein Zurück-Button und volle Bedienbarkeit per Tastatur.
* <strong>Fortsetzbar und teilbar.</strong> Der aktuelle Schritt und die Antworten werden in der URL und im Tab-Speicher gehalten, sodass ein Neuladen deinen Stand behält und ein vollständig beantworteter Link direkt beim Ergebnis öffnet.

= Settings =

Eine Einstellungsseite (Menü „Finder“, für WooCommerce-Berechtigungen) lässt dich:

* Das Quiz aktivieren oder deaktivieren, Überschrift, Unterüberschrift und Akzentfarbe festlegen und wählen, ob der Preis auf der Ergebniskarte erscheint.
* Die Schritte aufbauen: Jeder Schritt ist eine Frage mit Optionen zur Einfachauswahl; der „Wert“ einer Option ist ein kurzer Slug, der die Antwort identifiziert.
* Ergebnisse zuordnen: Erzeuge aus deinen Schritten eine Zeile pro Antwortkombination und wähle dann für jede das empfohlene Produkt, optional mit Überschrift, Beschreibung und Button-Beschriftung. Ein Hinweis zeigt auf einen Blick, wie viele Kombinationen noch ein Produkt brauchen.
* Ein Ersatzprodukt festlegen, das angezeigt wird, wenn keine Kombination passt oder ein zugeordnetes Produkt nicht verfügbar ist.

= Translation ready =

Alle Zeichenketten sind über die Textdomain `plogins-finder` übersetzbar, und eine Vorlage `plogins-finder.pot` liegt im Verzeichnis `/languages`. Beim Löschen des Plugins werden seine Optionen entfernt.

= How it works =

Das Quiz wird serverseitig gerendert (damit es mit der Seite zwischengespeichert werden kann), das Weiterschalten zwischen den Schritten passiert vollständig im Browser, ohne Anfrage an den Server. Nur die endgültige Empfehlung wird abgerufen, per REST-Anfrage aus derselben Domain an deine eigene Website, und frisch gerendert, sodass Preis und Lagerbestand aktuell sind. CSS und JavaScript werden nur auf Seiten geladen, die den Shortcode `[finder]` enthalten.

== Installation ==

1. Lade das Plugin nach `/wp-content/plugins/finder` hoch oder installiere es über Plugins → Installieren.
2. Aktiviere es. WooCommerce muss aktiv sein.
3. Öffne das Menü <strong>Finder</strong> im WP-Adminbereich, baue deine Schritte auf, generiere die Kombinationen und wähle für jede ein Produkt, und aktiviere dann das Quiz.
4. Füge das Quiz mit dem Shortcode `[finder]` auf einer beliebigen Seite oder in einem Beitrag ein.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Ja. Finder erfordert eine aktive WooCommerce-Installation.

= Does it use jQuery? =

Nein. Das eigene Frontend-Skript des Plugins ist reines JavaScript ohne jQuery-Abhängigkeit.

= Where do I put the quiz? =

Überall dort, wo der Shortcode `[finder]` funktioniert, auf einer Seite, in einem Beitrag oder in einem Rohes-HTML-/Shortcode-Block eines Page-Builders.

= How many questions can I ask? =

So viele Schritte, wie du möchtest, jeder mit beliebig vielen Optionen zur Einfachauswahl. Zwei oder drei kurze Schritte konvertieren am besten.

= What happens at the end? =

Die Kundin oder der Kunde sieht ein empfohlenes Produkt, dessen Bild, einen optionalen Preis, eine optionale Überschrift und Beschreibung sowie einen Button zur Produktseite. Passt keine Kombination oder ist das zugeordnete Produkt nicht verfügbar, wird dein Ersatzprodukt angezeigt.

= Is the price on the result always up to date? =

Ja. Die Empfehlung wird bei jeder Anfrage live über REST abgerufen und nie zwischengespeichert, sodass Preis und Lagerbestand deinen Shop in diesem Moment widerspiegeln.

= Does this plugin work on WordPress Multisite? =

Ja. Aktiviere es netzwerkweit oder auf einzelnen Websites; jede Website behält ihre eigenen Einstellungen.

== Screenshots ==

1. Das Schritt-für-Schritt-Finder-Quiz im Shop.
2. Die Empfehlungskarte mit dem Produkt und einem Call-to-Action.
3. Der Finder-Einstellungsbildschirm: Schritt-Editor und Ergebniszuordnung.

== External Services ==

Finder verbindet sich mit keinem externen Dienst oder Server Dritter und sendet keine Daten dorthin. Es bündelt kein SDK, keinen API-Client, keine Web-Schriftart, keine Kartenkachel, kein CDN-Asset und keinen Analyse-Aufruf, alles läuft auf deiner eigenen Website.

Alle Daten bleiben in deiner WordPress-Datenbank: die Fragen, Optionen, die Ergebniszuordnung und die Einstellungen liegen in der Option `finder_settings` (wobei `finder_db_version` die Schema-Version verfolgt). Wenn eine Kundin oder ein Kunde das Quiz abschließt, werden die Antworten per REST-Anfrage aus derselben Domain an den Endpunkt `/wp-json/` deiner Website gesendet, der die Empfehlung zurückgibt; eine ausgehende HTTP-Anfrage findet nie statt. Der aktuelle Schritt und die Antworten werden zudem in der Seiten-URL und im Tab-Sitzungsspeicher des Browsers gehalten, sodass das Quiz fortsetzbar ist. Beim Löschen des Plugins werden seine Optionen entfernt.

== Changelog ==

= 1.0.2 =
* Erste stabile Version: geführtes Schritt-für-Schritt-Produktfinder-Quiz mit einem freundlichen Schritt-/Ergebnis-Bereich, REST-gestützter Live-Empfehlung, barrierefreiem Frontend in reinem JavaScript und ohne Layout-Verschiebung. Optionsanzeigen im Radio-Stil, dezente Animation (berücksichtigt reduzierte Bewegung) und eine polnische Übersetzung.
