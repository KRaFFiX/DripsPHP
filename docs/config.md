# Konfiguration

Die Konfigurationsdateien befinden sich im `core/config`-Verzeichnis und werden im `ini`-Format gespeichert.

Grundsätzlich kann es mehrere Konfigurationsdateien geben, jedoch wird festgelegt, welche geladen werden soll.

Auf jeden Fall muss eine `default.ini`-Datei vorhanden sein, welche sozusagen die Standard-Konfiguration vorgibt. Diese Datei wird immer geladen.

Außerdem kann eine zusätzlich Konfigurationsdatei geladen werden, welche die Konfiguration der Standard-Konfiguration überschreibt.

Welche Konfigurationsdatei geladen werden soll, wird in folgender Datei angegeben: `core/lib/App.class.php`.

In der PHP-Klasse befindet sich eine Zeile die in etwa wie folgt aussieht:

```php
<?php
public static $configEnv = 'dev';
```

Anstatt von `dev` kann hier, der Name der Konfigurationsdatei angegeben werden, jedoch **ohne** der Endung `.ini`.

## Konfigurationsmöglichkeiten

### Datenbank

 - **db-type:** legt den Datenbank-Typ (z.B.: sqlite, myssql, mssql, ...) fest
 - **db-host:** legt den Host der Datenbank fest (z.B.: localhost) - wird SQLite verwendet, dann ist hier der Dateipfad anzugeben
 - **db-user:** legt den Benutzer der Datenbank fest (bei SQLite nicht notwendig)
 - **db-password:** legt das Passwort für den Datenbank-Benutzer fest (bei SQLite nicht notwendig)
 - **db-port:** legt den Port fest, über den auf die Datenbank zugegriffen werden soll (bei SQLite nicht erforderlich)

### Mail

 - **mail-smtp:** true/false - ob ein SMTP-Server, zum Versenden von Emails, verwendet werden soll
 - **mail-smtp-auth:** wenn ein SMTP-Server verwendet werden soll, kann festgelegt werden, ob für diesen eine Authentifizierung erforderlich ist
 - **mail-smtp-host:** legt den Host des SMTP-Servers fest
 - **mail-smtp-user:** legt den Benutzer des SMTP-Servers fest
 - **mail-smtp-password:** legt das Passwort für den Benutzer des SMTP-Servers fest
 - **mail-smtp-port:** legt den Port des SMTP-Servers fest
 - **mail-smtp-secure:** legt die Verschlüsselung (z.B.: tls) des SMTP-Servers fest

### Debug

 - **debug-on:** true/false - ob das Debugging-System des Frameworks verwendet werden soll
 - **debug-bar:** true/false - ob die Debug-Bar angezeigt werden soll
 - **debug-bar-phpinfo:** true/false - ob die PhpInfo in der Debug-Bar angezeigt werden soll

### Mehrsprachigkeit

 - **lang-default:** legt die Standard-Sprache und Region fest (z.B.: de-DE)

### Datum und Uhrzeit

 - **date-format:** legt das Datums-Format fest (z.B.: d.m.Y => siehe date-Funktion von PHP)
 - **date-time-format** legt das Zeit-Format fest (z.B.: H:i => siehe date-Funktion von PHP)
 - **date-timezone:** legt die Zeitzone fest (z.B.: Europe/Berlin => siehe Zeitzonen von PHP)
