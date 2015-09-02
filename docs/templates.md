# Templates

Als Template wird jene Datei bezeichnet welche, bei einem bestimmten Package, im `views`-Verzeichnis liegt.

Eine Template hat die Dateiendung `.php` und ist eine normale PHP-Datei, die jedoch von der integrierten Template-Engine von Drips ebenfalls compiliert werden kann.

Grundsätzlich kann PHP-Code übersetzt werden. Hinzu kommen noch einige Features, wie z.B.: das Platzhalter gesetzt werden können, welche im Controller beim Anlegen der View zugewiesen werden können.

## Platzhalter

Im Template werden Platzhalter mit folgender Syntax definiert: `{{{ platzhalter_name }}}`.

Die Platzhalter können mit Werten versehen werden, indem man auf das entsprechende View-Objekt eine Zuweisung durchführt.

``` php
<?php
// ...
$myView = new View();
// {{{ date }}} wird im Template durch das aktuelle Datum ersetzt.
$view->assign("date", date("d.m.Y"));
return $view->make("mypackage.views.myview");
// ...
```

Es können auch Arrays und Objekte zugewiesen werden.

Auf die einzelnen Array-Elemente kann wie folgt zugegriffen werden: `{{{ name.key }}}`.

## Methoden-Aufrufe

Wird der View ein Objekt zugewiesen (z.B.: ein Form-Objekt) so können Methoden des Objekts (die keine Parameter benötigen!) direkt im Template mit folgender Syntax aufgerufen werden:

``` html
<!-- objname.method() -->
```

## Plugins

Für die Template-Engine können Plugins erstellt werden. Hierfür muss lediglich eine Klasse angelegt werden, welche das Interface `DripsPHP\MVC\IViewPlugin` implementiert. Anschließend muss die Klasse als ViewPlugin registriert werden. Dies ist wiederum auf 2 Arten möglich. Entweder man legt die Klasse im Verzeichnis `core\lib\MVC\ViewPlugins` mit der Endung `.class.php` ab oder man registriert sie manuell.

``` php
<?php
// ...
use namespace\myPlugin;

$plugin = new myPlugin();
ViewPlugin::register("namespace\\myPlugin", $plugin);
// ...
```

*Dieses Code-Stück kann beispielsweise in einer `autoload.php`-Datei angelegt werden*.

Drips liefert standardmäßig vorgefertigte Plugins mit.

### Blocks

Das Blocks-Plugin ermöglich die Vererbung von Templates. Dafür können sogenannte Blöcke definiert werden, welche später von einem Template ersetzt werden können.

Damit von einem Template geerbt werden kann wird folgende Syntax verwendet: `<!-- @EXTENDS(package.views.myview) -->`.

Im Haupt-Template (von dem geerbt wird) müssen Blöcke definiert sein, die anschließend überschrieben werden können. Diese werden wie folgt definiert. Um einen Block zu öffnen muss so vorgehen: `<!-- @SECTION(name) -->` und um ihn wieder schließen zu können muss einfach folgende Zeile hinzugefügt werden: `<!-- @END(name) -->`. Der Name gibt hierbei einfach den Namen des Blocks an, damit man den jeweiligen Block eindeutig identifizieren kann.

Um die Blöcke von einem anderen Template überschreiben zu können, muss lediglich von dem Template geerbt werden und die Syntax für die Blöcke auch im anderen Template definiert werden.

Wird ein Block nicht überschrieben, so wird der Wert des übergeordneten Templates beibehalten.

### Mehrsprachigkeit

Drips verfügt über ein System, wodurch mehrsprachige Webanwendungen erstellt werden können. Damit diese auch in Templates sinnvoll eingesetzt werden kann, gibt es die Möglichkeit diese direkt in das Template zu integrieren.

Dabei wird lediglich folgende Syntax verwendet:

`@TRANSLATE[key.key2.key3](param1, param2, param3)` die Parameter sind bei dieser Schreibweise optional.

### Links

Mithilfe des Links-ViewPlugins ist es möglich Links über das Template-System zu erzeugen. Dies ist mit einem `Redirect::link("...")` gleichzusetzen. Dafür wird folgende Syntax verwendet: `@LINK(name_der_route)`.

### Assets

Mithilfe des Assets-ViewPlugins ist es, ähnlich wie beim Links-Plugin möglich, über das Template-System die Asset-Pfade zu generieren. D.h. anstatt ein Asset (z.B.: CSS-Datei, Bild oder JavaScript) als direkten Pfad anzugeben kann dieser generiert werden über die ASSET-Funktion: `Redirect::asset("…")`. Dafür wird folgende Syntax verwendet: `@ASSET(pfad [, package])`.

Soll beispielsweise ein Asset-Pfad für `src/my_package/assets/img/logo.png` erzeugt werden, so wird die Funktion wie folgt im Template aufgerufen: `@ASSET(img/logo.png)` wird das Template nicht vom aktuellen Package aufgerufen kann als 2. Parameter noch das entsprechende Package festgelegt werden.