# Datenbankzugriff

Für den Datenbankzugriff bietet Drips mehrere Möglichkeiten. Grundsätzlich basiert jede Datenbank-Verbindung auf einem `DripsPHP\Database\Database`-Objekt. Dieses Objekt gleicht dem [Medoo-Framework](http://medoo.in). Der große Unterschied besteht jedoch darin, dass die eigentlich Datenbank-Verbindung erst dann aufgebaut wird, wenn sie auch benötigt wird. D.h. nur wenn auch eine Abfrage an den Server gesendet wird. Andernfalls wird keine Verbindung aufgebaut.

Drips sieht eine Haupt-Datenbank vor, auch wenn Drips vollkommen ohne Datenbank läuft. Die Haupt-Datenbank entspricht einer statischen Klasse (`DripsPHP\Database\DB`). Dabei greift man mit Hilfe von statischen Methoden prinzipiell direkt auf das Database-Objekt zu, jedoch ist es von überall aus ansprechbar. Die Verbindung der Haupt-Datenbank wird automatisch aufgebaut, anhand der Daten, die in der Konfiguration festgelegt wurden.

## ORM (object-relational mapping)

Drips bietet zusätzlich ein Verfahren zum Zugriff auf die Datenbank mittels Objekten. Dabei entspricht ein Objekt einem bestimmten Datensatz. Ein sogenannter Container verwaltet die einzelnen Objekte und managed die Datenbank-Verbindung.

Um das ORM-System von Drips verwenden zu können müssen zunächst das sogenannte Entity-Objekt (das dem Datensatz entspricht) sowie der entsprechende Entity-Container angelegt werden. Beide Klassen können von Drips generiert werden. Dafür wird ein bestimmtes JSON-Format vorgeschrieben.

### Beispiel

> Das Beispiel demonstriert eine Benutzer-Tabelle. Zuerst wird der Name des Entities festgelegt, dieser ist gleichzeitig der Klassen-Name des Entities. Weiters wird eine Tabelle festgelegt, welche dem Tabellen-Namen der Datenbank entspricht um das Objekt mit der Datenbank verknüpfen zu können. Außerdem wird der Name des Containers festgelegt.

```json
{
    "name": "user",
    "table": "user",
    "container": "users",

    "attributes": {

        "id": {
            "options": ["primary", "auto"],
            "type": "int"
        },

        "username": {
            "type": "text"
        },

        "password": {
            "type": "text"
        }

    }
}
```

> Das Entity verfügt grundsätzlich über verschiedene Attribute (Spalten in der Tabelle). Diese müssen entsprechend niedergeschrieben werden.

Bei einem Attribut muss in erster Linie ein Name festgelegt werden, welcher dem der Tabellen-Spalte entsprechen muss. Mit `type` kann der Datentyp des Attributes festgelegt werden.

**Datentypen:**
 - `int` - ganzzahlige Werte
 - `text` - einfacher String
 - `string` - genau wie text (dient nur als Alias)
 - `float` - Gleitkomma Werte
 - `boolean` - 0 oder 1

Neben dem Datentyp können auch `options` spezifiert werden.

**Options:**
 - `primary` - Primärschlüssel
 - `auto` - Auto_Increment (automatischer Zähler)
 - `not_null` - Wert darf nicht *null* sein

Es können auch Standardwerte mittels `default` festgelegt werden. Als Standardwert kann auch ein definierter Platzhalter verwendet werden.

**Platzhalter:**
 - `{date}` - liefert das aktuelle Datum
 - `{time}` - liefert die aktuelle Uhrzeit
 - `{datetime}` - liefert Zeit und Datum

Außerdem kann eine Länge angegeben werden (max. Zeichenlänge) mit `length`.

Zusätzlich können einfache Fremdschlüssel angegeben werden. Hierfür wird einfach als `references` der Namespace des **Referenz-Entity-Containers** festgelegt. (Punkt-Notation => `mypackage.entities.myReference`)

Außerdem können einem Attribut Validators zugewiesen werden.

### Beispiel

```json
"validators": {
    "required": "",
    "maxlength": "30"
}
```

Anhand der fertigen JSON-Datei kann mit folgendem CLI-Kommando ein Entity sowie ein Entity-Container generiert werden.

```
php drips create:Entity pfad/zur/json-datei.json
```

Anschließend werden die beiden PHP-Klassen im gleichen Verzeichnis, in dem sich auch die JSON-Datei befindet abgelegt.

Die Entity-Klasse verfügt grundsätzlich nur über Getter und Setter. Wobei die Werte nur gesetzt werden können, wenn Sie den Angaben in der JSON-Datei entsprechen.

Möchte man das Entity beispielsweise in die Datenbank einfügen, muss das Entity-Objekt entsprechend über den Entity-Container eingefügt werden.

## Kompatibilität

Die Verbindung zu verschiedenen Datenbank-Systemen ist nicht getestet worden. Grundsätzlich läuft der Verbindungsaufbau über PDO, wodurch grundsätzlich mehrere Datenbank-Systeme untersützt werden. Je nach Datenbank muss ein entsprechender Treiber installiert sein.

Hauptsächlich werden SQLite und MySQL unterstützt.
