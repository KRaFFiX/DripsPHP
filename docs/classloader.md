# ClassLoader

Das Framework besteht aus sehr vielen PHP-Klassen. Genauso wie die spätere Webanwendung. Jedoch sind nicht immer alle Dateien notwendig. Aus diesem Grund ist der ClassLoader für ein automatisches Laden der PHP-Klassen, wenn diese benötigt werden, verantwortlich.

Damit der ClassLoader richtig funktionieren kann, muss einerseits der Name der Datei bestimmten Richtlinien entsprechen und ein entsprechender Namespace verwendet werden.

Grundsätzlich unterscheidet man beim Laden von Klassen 4 Kategorien:

 - Core
 - CMD
 - Plugin
 - Src

## Core

Als Core-Klasse versteht sich jede Klasse, die sich in `core/lib` befindet. Dabei muss jede PHP-Klasse, die vom ClassLoader geladen werden können soll, die Datei-Endung `.class.php` haben.
Außerdem muss der Namespace dem Dateipfad der Datei entsprechen, jedoch anstatt von `core/lib` mit `DripsPHP` beginnen.
Beispielsweise für die Datei `core\lib\MVC\Controller.class.php` den Namespace `DripsPHP\MVC` benötigen, dass diese entsprechend geladen werden kann.

## CMD

Ein CMD ist eine Kommando-Klasse für das CLI. Damit können also Befehle für das CLI von Drips erstellt werden. Damit eine solche Klasse geladen werden kann, sind folgende Richtlinien zu beachten.
Wie bei Core-Klassen entspricht der Namespace dem Dateipfad zur Klasse, wobei je nachdem wo die Datei liegt andere Regelungen in Kraft treten können. Liegt die CMD-Datei beispielsweise im `core/lib`-Verzeichnis so muss ebenfalls der `DripsPHP`-Namespace verwendet werden.
Es ist jedoch zu berücksichtigen dass ein CMD immer die Dateiendung `.cmd.php` aufweisen muss um als CMD vom System erkannt werden zu können.

## Plugin

Die Regeln für Plugin-Klassen sind relativ simpel, da diese lediglich die Dateiendung `.php` benötigen und der Namespace dem Dateipfad entspricht und dementsprechend mit `plugins` beginnen muss.
Der wesentliche Vorteil an diesen Klassen ist, dass diese nur vom ClassLoader geladen werden, wenn das entsprechende Plugin auch aktiviert ist.

## Src

Src-Klassen geben ebenfalls als Namespace den Dateipfad an, jedoch wird dabei das `src`-Verzeichnis ignoriert. D.h. wenn eine Klasse z.B.: unter `src/myPackage/controllers/Controller.php` gespeichert wird, so ist folgender Namespace anzugeben: `myPackage\controllers`. Die PHP-Klassen haben die einfache Endung `.php`.
