# Model-View-Controller

Für den Aufbau einzelner Webseiten wird die MVC-Architektur verwendet. Dabei werden die Daten, sowie die Ausgabe und die Verarbeitung möglichst voneinander abstrahiert.

Grundsätzlich verweist jede Route auf eine Controller-Methode. Die Methode des Controllers erzeugt anschließend eine View (d.h. also im Prinzip eine Ausgabe). Der Controller kann dabei auf verschiedene Daten zugreifen (Model).

Drips sieht hierfür im Package die entsprechenden Verzeichnisse `controllers`, `models` und `views` vor.

## Controller

Beim Anlegen eines Controllers muss lediglich eine PHP-Klasse im `controllers`-Verzeichnis, des jeweiligen Packages mit dem entsprechenden Namespace angelegt werden.
Anschließend muss die Klasse von der Klasse `DripsPHP\MVC\Controller` erben.

Möchte man einen Konstruktor für einen Controller anlegen sollte man einfach eine Methode: `init()` verwenden, da diese automatisch vom Controller im Konstruktor aufgerufen wird.

Ein Controller verfügt grundsätzlich über ein View-Objekt, auf das mittels `$this->view` innerhalb des Controllers zugegriffen werden kann. Dadurch kann der Controller beispielsweise eine grundlegende Konfiguration an der View vornehmen, die eventuell gemeinsam verwendet wird.

Auf jeden Fall sollte der Rückgabewert der Ausgabe entsprechen, obwohl auch die Ausgabe innerhalb der Methode ausgegeben wird.

### Beispiel

```php
<?php

namespace mypackage\controllers;

use DripsPHP\MVC\Controller;

class TestController extends Controller
{
        public function GET()
        {
            return "Hello World.";
        }
}
```

## Views

Eine View ist ein Objekt, dass zur Übersetzung eines bestimmten Templates verwendet wird und repräsentiert die Ausgabe von Drips.

Möchte man eine View anlegen, so muss ein View-Objekt erzeugt werden. Anschließend können beispielsweise Platzhalter mit Werten versehen werden, sodass diese im Template entsprechend ersetzt werden können. Zum Schluss muss die View durch die Angabe eines Templates generiert werden.

Ähnlich wie bei einem Controller wird beim Generieren der View (`make`) der Klassennamen (mit Namespace) mit der gleichen Notation wie beim Routing angegeben.

### Beispiel

```php
<?php
// ...
$myView = new View();
$view->assign("date", date("d.m.Y"));
return $view->make("mypackage.views.myview");
// ...
```

## Model

Ein Model ist ein Objekt, dass lediglich bestimmte Daten zu Verfügung stellt und ändern kann.

Ein Model kann mittels integrierter Funktion in JSON exportiert werden, was beispielsweise bei Restful-Webservices zum Einsatz kommen könnte. Dafür muss allerdings das Model im `models`-Verzeichnis mit dem entsprechenden Namespace untergebracht sein und von der Model-Klasse von Drips erben. (`DripsPHP\MVC\Model`)
