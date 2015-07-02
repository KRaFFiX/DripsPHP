# Caching

Oftmals werden Seiten dynamisch generiert, obwohl sie nur statische Inhalte aufweisen. Oftmals ist eine Abfrage auch sehr aufwändig und sollte deshalb nicht zu oft hintereinander erfolgen. In diesen Fällen bietet Drips das Caching an.

Durch das Caching können bestimmte Inhalte und Daten zwischengespeichert werden um beispielsweise nicht erneut eine Datenbank-Abfrage ausführen zu müssen.

Grundsätzlich unterscheidet Drips zwischen 3 verschiedenen Caching-Systemen:

 - CompileCache
 - DataCache
 - ViewCache

Im Prinzip funktionieren alle gleich. Es wird eine Funktion angegeben die den Inhalt ermittelt und eine Bedingung, wann diese Funktion ausgeführt werden muss und wann es aus dem Cache geholt werden soll. Hierbei können natürlich jederzeit eigene Caches implementiert werden.

## CompileCache

Der CompileCache ist relativ einfach zu verwenden. Sein Haupteinsatzgebiet ist das Cachen von Dateien, welche im Cache nur aktualisiert werden muss, wenn sich der Inhalt ändern würde.

Beispielsweise kann der CompileCache verwendet werden um LESS-Dateien zu kompilieren und anschließend zu cachen, da die CSS-Datei nur neu generiert werden muss, sobald die LESS-Datei geändert wurde.

### Beispiel

```php
<?php
// ...
$lessFile = 'test.less';
$cache = new CompileCache('unittest-test.less', $lessFile);
echo $cache->get(function () use ($lessFile) {
    // Rückgabe des kompilierten LESS-Files
});
// ...
```

## DataCache

Der DataCache dient zur Speicherung von großen oder komplexen Daten, die nicht immer neu abgefragt oder berechnet werden sollen. So können die Daten in einem Cache abgelegt werden und einfach regelmäßig neu generiert werden.
Beim Anlegen des Caches muss festgelegt werden nach welcher Dauer die Daten ungültig sind und erneut abgefragt oder berechnet werden müssen. Das Format für die Dauer entspricht dem Format des DateIntervals in PHP.

### Beispiel

```php
<?php

$cache = new DataCache('unittest-datacache', 'PT2S'); // 2 Sekunden
$myData = $cache->get(function () {
    return array(1, 2, 3, 4, 5);
}, true);
```

## ViewCache

Der ViewCache ist prinzipiell genauso wie der DataCache, d.h. der Inhalt des Caches wird nach einem bestimmten Zeitinterval neu generiert. Der wesentliche Unterschied ist jedoch, dass eine View zurückgeliefert wird und eigentlich keine Daten.

### Beispiel

```php
<?php
$view = new View();
$view = $view->make("my.tpl");
$cache = new ViewCache('unittest-viewcache', 'PT2S');
$myView = $cache->get(function () use ($view) {
    return $view;
});
```
