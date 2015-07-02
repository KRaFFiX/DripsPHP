# Aufbau bzw. Grundstruktur

## Verzeichnisstruktur

Der Aufbau von Drips lässt sich am einfachsten anhand der Verzeichnisstruktur erklären.

Im `core`-Verzeichnis befinden sich alle, für das Framework relevanten, Dateien. Es bildet also quasi den Kern von Drips.

Außerdem gibt es ein `src`-Verzeichnis. In diesem Verzeichnis soll die Webanwendung abgelegt werden, mit allen zugehörigen Dateien. Somit gibt es bereits eine klare Trennung zwischen Framework-Dateien und eigenen Dateien.

Das `plugins`-Verzeichnis ist, wie der Name bereits aussagt, für Plugins vorgesehen. Plugins müssen in diesem Verzeichnis abgelegt werden, damit sie von Drips erkannt und verwaltet werden können.

Zum Erstellen von Unit-Tests wird das `test`-Verzeichnis verwendet. Testfälle werden in dem Verzeichnis abgelegt, sodass diese gemeinsam ausgeführt werden können.

### Core

Das `core`-Verzeichnis untergliedert sich noch in weitere Verzeichnis, welche nun kurz erklärt werden:

 - **autoload:** diese Dateien werden automatisch zum Beginn der Ausführung von Drips geladen
 - **config:** beinhaltet die Konfigurationsdateien
 - **lib:** beinhaltet alle notwendigen PHP-Klassen
 - **log:** Verzeichnis zum Sammeln von System-Logs
 - **tmp:** temporäres Verzeichnis, welches beispielsweise für das Caching verwendet wird

## Ablauf eines Webseitenaufrufs

 1. Konfiguration laden und anwenden
 2. Autoloads ausführen
 3. Routing (auflösen der URL in einen Controller)
 4. Ausführen der entsprechenden Controller-Funktion oder Err404-Exception im Fehlerfall
 5. Anzeigen der Debug-Page, falls es zu einem Fehler kam bzw. hinzufügen der Debug-Bar, sofern diese aktiviert ist.
