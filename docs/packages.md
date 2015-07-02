# Packages

Als *Package* wird grundsätzlich jenes direkte Verzeichnis im `src`-Verzeichnis bezeichnet.

Möchte man eine große Anwendung entwickeln, so kann man diese in verschiedene Teilbereiche untergliedern und somit unterschiedliche Packages erstellen.

Grundsätzlich beinhaltet ein Package alle notwendigen Dateien, die für die Darstellung einer Webseite notwendig sind. Dabei muss ein Package folgende Verzeichnis beinhalten:

 - **assets:** zum Speichern von beispielsweise CSS-Dateien oder Bildern, aber auch JavaScripts, usw.
 - **controllers:** beinhaltet alle Controller eines Packages
 - **entities:** beinhaltet alle Entities, sowie die Entity-JSON-Dateien für die entsprechenden Entities und Entity-Container
 - **langs:** beinhaltet Sprachpakete für mehrsprachige Webseiten
 - **models:** beinhaltet alle Models (außer Entities)
 - **views:** beinhaltet alle Templates

Außerdem muss ein Package über eine `routes.json`-Datei verfügen, in der alle Routen der Anwendung definiert werden.

Zusätzlich kann eine `autoload.php`-Datei erstellt werden (optional) - diese wird automatisch zu Beginn des Frameworks geladen.

Das grundlegende Package-Gerüst kann über das CLI erzeugt werden.
