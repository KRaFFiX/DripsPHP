# Authentifizierung

Nahezu jede dynamische Homepage verfügt über einen Login. Auch hierbei kann untersützt Drips durch ein vorgefertigtes Authentifizierungssystem.

Zur Authentifizierung wird das ORM- bzw. Entity-System von Drips verwendet.

Möchte man einen eigenen Login bzw. eine eigene Authentifizierung erstellen, so empfiehlt es sich, hierfür eine neue Klasse anzulegen, die von der ursprünglichen Authentifizierungs-Klasse erbt. (`DripsPHP\Security\Auth`)

Über die Attribute der Klasse kann der Name der Authentifizierung festgelegt werden (=Schlüssel in der Session). Außerdem kann der entsprechende Entity-Container festgelegt werden, über welchen die Authentifizierung erfolgen soll.

## Beispiel

```php
<?php
// ...
Auth::setEntityContainer('package.entities.Users');
$data = array('username' => 'test', 'password' => 'test');

return Auth::login($data);
// ...
```
