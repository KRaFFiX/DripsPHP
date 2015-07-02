# Redirecting

Jeder der bereits eine Webseite erstellt hat weiß, dass die Verlinkungen oftmal sein großes Problem darstellen. Absolut oder relativ?

Durch das integrierte Routing-System von Drips können durch die `Redirect`-Klasse Umleitungen angefordert oder Links generiert werden.

## Link generieren

Zum Generieren von Links wird die `link`-Methode verwendet.

### Beispiel

```php
<a href="<?= Redirect::link('my-package-home'); ?>">Home</a>
```

> Als Parameter wird der Name der Route angegeben.

## Weiterleitungen

Weiterleitungen können auf eine bestimmte URL oder auf eine bestimmte Route erfolgen.

```php
<?php
// ...
// Weiterleitung auf eine URL
Redirect:toURL("http://google.at");

// Weiterleitung auf eine Route
Redirect::toRoute("my-package-home");
// ...
```

> Bei der Weiterleitung auf eine Route kann als 2. Parameter ein Array mit Parametern angegeben werden, so wie sie in der Route angegeben werden können.
