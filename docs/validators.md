# Validators

Validators sind eine Möglichkeit Strings zu überprüfen. Mit Hilfe der Validator ist eine einheitliche Eingaeprüfung möglich.

Grundsätzlich ist ein Validator eine Klasse, die eine `validate`-Methode zur Verfügung stellt und dabei entweder `true` oder `false` liefert.

Alle verfügbaren Validators befinden sich im Verzeichnis: `core/lib/Validator/Validators`.

Ein Validator muss das Interface `DripsPHP\Validator\IValidator` implementieren.

Um die Methode verwenden zu können ruft man sie nicht einfach auf, sondern erzeugt ein Überprüfungs-Objekt, den sogenannten Validator. (`DripsPHP\Validator\Validator`)
