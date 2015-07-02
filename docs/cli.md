# CLI

Das CLI (Command Line Interface) ermöglich die Steuerung von Drips über die Konsole. Hierbei können folgende Befehle verwendet werden:

Um eine Liste mit allen verfügbaren Kommandos anzeigen zu lassen sind folgende Befehle notwendig:

 1. in das richtige Verzeichnis navigieren:

 ```
 cd pfad/zum/drips-verzeichnis
 ```  

 2. Drips-Hilfe aufrufen:

 ```
 php drips help
 ```

Anschließend sieht man eine Übersicht von allen *Types* und deren *Actions*. Die Syntax steht ebenfalls in der Hilfe und sieht wie folgt aus:

```
php drips {action}:{type} [param1] [param2] [...]
```

Außerdem verfügt jedes Kommando über eine Hilfe-Funktion, somit können nähere Details über das Kommando abgerufen werden.

### Kommandos erstellen

Selbstverständlich können auch selbst Kommandos erstellt werden. Dafür legt man einfach eine `.cmd.php`-Datei an. In dieser Datei befindet sich ein PHP-Klasse die das Interface `DripsPHP\CLI\ICMD` implementieren muss.
