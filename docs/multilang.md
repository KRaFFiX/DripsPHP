# Mehrsprachigkeit

In der heutigen Zeit ist es üblich, dass eine Webseite mehrsprachig ist. Auch dabei kann Drips Abhilfe schaffen.

Jedes Package verfügt über ein `langs`-Verzeichnis. In dem Verzeichnis werden JSON-Dateien für die unterschiedlichen Sprachen angelegt, z.B.: `de-de.json`. Das bedeutet, dass es eine deutschsprachige Übersetzung für Deutschland ist, weiters kann natürlich auch `de-at.json` für Österreich angelegt werden.

Je nach Sprache des Benutzers kann die Sprache teilweise automatisch erkannt werden. Zum Übersetzen wird entweder die Template-Engine verwendet oder man greift auf das Dictionary zu.

## Beispiel (Dictionary)

```php
<?php
// ...
echo App::$dictionary['actions']['new']; // gibt z.B.: 'Neu' aus
// ...
```
