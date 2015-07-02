# Converter

Um bestimmte Einheiten oder Formate in andere umwandeln zu können werden Converter eingesetzt. Jeder Converter muss das Interface `DripsPHP\Converter\IConverter` implementieren.

## Beispiel (FilesizeConverter)

```php
<?php
// ...
// Konvertierung von 1000000 Byte in MB
echo FilesizeConverter::convert(1000000, "Byte", "MB");
// ...
```
