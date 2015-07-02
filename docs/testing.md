# Testing

Drips ist vollständig objektorientiert. Aus diesem Grund können einzelne Klassen getestet werden. Dafür bietet Drips ein kleines Testing-System an.

Grundsätzlich sollten die sogenannten Testfälle, welche jeweils in einer eigenen Klasse gespeichert werden sollen, im Verzeichnis `test` abgelegt werden.

Zuerst muss eine Klasse angelegt werden, welche von `DripsPHP\Debug\UnitTest` erbt. Alle Methoden der Klasse müssen `true` oder `false` zurückliefern, da diese die einzelnen Test-Szenarien repräsentieren.

Sind die Testfälle angelegt, so kann mit Hilfe des CLI ein Testlauf gestartet werden.

```
php drips start:Test
```

> Hinten kann eventuell noch ein Datei- oder Verzeichnispfad angehängt werden, um nur eine bestimmte Datei oder ein bestimmtes Verzeichnis überprüfen zu lassen, andernfalls wird das gesamte Testverzeichnis ausgeführt.

## Beispiel

**Validator-Test**

```php
<?php
namespace test\core;

use DripsPHP\Debug\UnitTest;
use DripsPHP\Validator\Validator;

class ValidatorTest extends UnitTest {
    public function testEmailValidatorValid() {
		$validator = new Validator("email");
		$validator->set("test@email.com");
		return $validator->validate();
	}

	public function testEmailValidatorInvalid() {
		$validator = new Validator("email");
		$validator->set("email");
		return !$validator->validate();
	}
}
```
