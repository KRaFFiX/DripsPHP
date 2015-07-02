# Routing

In diesem Zusammenhang bedeutet Routing, die Auflösung einer URL, die aufgerufen wurde, zu einer entsprechenden Webseite.

Als Entwickler möchte man seine URLs gerne selbst festlegen können und genau das ist das Ziel des Routings. Eine einfache Möglichkeiten URLs mit einzelnen Seiten zu verknüpfen.

## Funktionsweise

In jedem Package befindet sich eine `routes.json`-Datei, in welcher alle Routen des entsprechenden Packages gespeichert werden.

Eine Route beinhaltet mindestens einen Namen, eine URL sowie eine Callback-Methode, die aufgerufen werden soll.

Der Ausschnitt einer solchen Datei könnte wie folgt aussehen:

```json
{
    "home": {
        "url": "/",
        "callback": "mypackage.controllers.MainController:index"
    }
}
```

In diesem Fall bewirkt der Aufruf von `/` das Ausführen der Methode `index()` von der Klasse: `mypackage\controllers\MainController`.

> Routen können jedoch auch mit Hilfe der `autoload.php` - direkt mit PHP - definiert werden.

Dies könnte beispielsweise wie folgt aussehen:

```php
<?php
use DripsPHP\Routing\Route;
use mypackage\controllers\MainController;

// Anlegen einer neuen Route
$testRoute = new Route("home", "/");
// Hinzufügen einer Callback-Methode
$testRoute->addCallback("mypackage.controllers.MainController:index");
// Route beim RequestHandler registrieren
$testRoute->register();
```

Der Vorteil dieser Methode besteht darin, dass nicht zwingend ein String mitgegeben werden muss, sondern auch direkt eine Funktion angegeben werden kann, z.B.:

```php
<?php
use DripsPHP\Routing\Route;

// Anlegen einer neuen Route
$testRoute = new Route("test", "/test");
// Hinzufügen einer Callback-Methode
$testRoute->addCallback(function(){
    echo "Das ist ein Test!";
});
// Route beim RequestHandler registrieren
$testRoute->register();
```

### Callback ohne Methode

Eine Route kann auch eine Callback aufweisen (z.B.: `mypackage.controllers.MainController`) ohne, dass eine Methode angegeben ist. (*normalerweise durch einen `:` gekennzeichnet*)
In diesem Fall wird automatisch die HTTP-Request-Methode aufgerufen, d.h. bei einem GET-Request wird automatisch die Methode `GET()` des angegebenen Controllers ausgeführt - bei einem POST-Request => `POST()`

### Dynamische Routen

In der Praxis sind Routen oftmals dynamisch. Aus diesem Grund bietet Drips die Möglichkeit Routen mit Platzhaltern zu definieren. Dies würde wie folgt aussehen:

```json
{
    "user-profile": {
        "url": "/profile/{username}",
        "callback": "mypackage.controllers.UserController:showProfile"
    }
}
```

Im obigen Beispiel wird der Aufruf von URLs, die mit `/profile` beginnen und einen oder keinen Parameter (weitere Angabe) aufweisen, an den entsprechenden Controller weitergegeben.
Der entsprechende Controller könnte wie folgt aussehen:

```php
<?php

use DripsPHP\MVC\Controller;

namespace mypackage\controllers;

class UserController extends Controller
{
    public function showProfile($username = null)
    {
        if($username === null){
            echo "Kein gültiger Seitenaufruf!";
        } else {
            echo $username;
        }
    }
}
```

> **Wichtig:** die Route wird auch ausgelöst, wenn **kein** Benutzername angegeben wurde, sondern lediglich `/profile` aufgerufen wurde. In diesem Fall kann logischerweise kein Benutzername übergeben werden. Deshalb sollte man bei einem solchen Controller immer einen Standardwert festlegen.

### Domainabhängige Routen

Routen können auch domainabhängig sein. Das bedeutet, dass die Route nur ausgeführt werden kann, wenn sie unter der richtigen Domain ausgeführt wurde. In diesem Fall wird in der JSON-Datei einfach noch zusätzlich ein Array mit gültigen Domains angegeben bzw. eine gültige Domain angegeben.

```json
{
    "home": {
        "url": "/",
        "callback": "mypackage.controllers.MainController:index",
        "domain": "my-homepage.com"
    }
}
```

Im PHP-Code kann eine Domain mit der Methode `addDomain($domain)` (von einem `Route`-Objekt) hinzugefügt werden. Ist keine Domain angegeben, so ist die Route für alle Domains gültig.

### HTTPS-Only

Außerdem kann festgelegt werden, dass bestimmte Routen nur über HTTPS erreichbar sein sollen. In diesem Fall kann man in der JSON-Datei folgendes angeben:

```json
{
    "home": {
        "url": "/",
        "callback": "mypackage.controllers.MainController:index",
        "https": "true"
    }
}
```

Im PHP-Code ist dies ebenso mit der Methode `setHTTPS()` (auf ein `Route`-Objekt) möglich.

Wird nichts angegeben ist kein HTTPS notwendig.
