# Logging

Durch den Drips-Logger, können Logs mit den verfügbaren PHP-Funktionen ganz einfach erstellt werden.

### Beispiel

```php
<?php
// ...
$log = new Log("test-log");
$log->write("Logfile created.");
$log->clear();
// ...
```
