# Changelog


## v0.1.1

**ADDED:**

 - DripsCMS
 - Ab sofort kann in der Konfiguration ein Security-Token zur Verschlüsselung festgelegt werden
 - Links-ViewPlugin
 - Assets-Generieren + ViewPlugin
 - Inputs entfernen automatisch HTML-Tags (kann deaktiviert werden)
 - View-Klasse kann Assigns überschreiben oder erweitern

**BUGFIX:**

 - Fehler im Exception-Handling behoben 
     - Exceptions wurden nicht ordnungsgemäß im Debug-Screen angezeigt, stattdessen wurde einfach die Ausführung des Scripts unterbrochen.
 - Fehler beim Login behoben (Auth)
     - Der Login hat in Kombination mit verschiedenen Datenbank-Systemen nicht funktioniert
     - `getCurrentUser()` hat nicht ordnungsgemäß funktioniert
 - Fehler behoben beim Redirecting (falscher Pfad)
 - PhpInfo wird standardmäßig deaktiviert, weil es das Design überschreibt
 - Fehler behoben in der Form-Klasse
     - Validator-Results waren bei Entity-Feldern nicht korrekt