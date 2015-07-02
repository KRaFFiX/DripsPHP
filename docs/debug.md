# Debug-Utils

Um das Suchen und Finden von Fehlern zu vereinfachen werden Debug-Funktionen angeboten.

Dazu gehören:

 - Debug-Page
 - Debug-Bar
 - Dump

## Debug-Page

Ist die Debug-Page aktiviert, so können bestimmte Fehler von Drips abgefangen werden. Anschließend wird eine Fehlerseite erzeugt auf der detailierte Informationen über den Fehler eingeholt werden können.

## Debug-Bar

Ist die Debug-Bar aktiviert, so befindet sich auf jeder Seite eine Bar, mit nützliches Informationen.

## Dump

Die `DripsPHP\Debug\DebugUtils::dump`-Methode soll die Funktionen `var_dump` und `print_r` ablösen. Sie liefert eine formatierte und farbige Ausgabe über die mitgegebene Variable, z.B.: über ein Array.
