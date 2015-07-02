# Hashing

Zum Verschlüsseln oder eigentlichen Hashen der Passwörter stellt Drips eine eigene Hash-Klasse zur Verfügung. (`DripsPHP\Security\Hash`)

Durch die Klasse können entweder einfache Strings gehasht werden:

```php
<?php
// ...
echo Hash::generate("test");
// ...
```

> Als 2. Parameter kann noch ein Hashing-Algorithmus angegeben werden. Standardmäßig wird *sha512* verwendet.

## Verschlüsseln von Passwörtern

Passwörter oder ähnliche Benutzerinformationen werden oftmals gehasht, dass diese nicht mehr rückverschlüsselt werden können.
Hierfür stellt die Klasse die Methode `encrypt` zur Verfügung.

```php
<?php
// ...
echo Hash::encrypt("password", "exampleSalt");
// ...
```

> Beim Verschlüsseln muss zwingend ein sogenanntes Salt angegeben werden, mit Hilfe dessen verschlüsselt wird. Das Salt muss beim Verschlüsseln immer gleich sein, da sonst ein anderes Ergebnis zustande kommen würde.

> Das Salt kann auch für die Hash-Klasse selbst festgelegt werden. Ist dies der Fall wird das Salt verwendet, wenn keines festgelegt wurde.

## Zufälliger Hash

Außerdem können zufällige Hash-Werte erzeugt werden. Dafür kann die `random`-Methode verwendet werden.

```php
<?php
// ...
echo Hash::random();
// ...
```

> Als Parameter kann ein Hashing-Algorithmus übergeben werden. Standardmäßig wird *md5* verwendet.
