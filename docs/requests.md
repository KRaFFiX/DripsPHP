# HTTP-Requests

Oftmals fragt eine Webanwendungen Daten über HTTP von einem anderen Webserver ab. Hierfür bietet Drips eine vereinfachte Möglichkeit durch die `DripsPHP\HTTP\Request`-Klasse.

## Beispiel

```php
<?php
// ...
$request = new Request("http://example.com/api/login");
$request->post(array(
    "username" => "admin",
    "password" => "password"
));
$request->send();
// ...
```
