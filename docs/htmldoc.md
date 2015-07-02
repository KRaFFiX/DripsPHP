# HTML-Dokumente

Drips stellt eine Möglichkeit zur Verfügung HTML-Dokumente mit Hilfe eines PHP-Objekts zu erzeugen.

Dafür gibt es die Klasse: `DripsPHP\HTML\HTMLDocument`.

Die Klasse stellt Getter und Setter für einzelne Attribute, wie z.B.: den Titel des Dokuments zur Verfügung.

Die `__toString()`-Methode des Objekts erzeugt dabei das fertige HTML-Dokument, wodurch eigentlich nur das HTML-Dokument-Objekt ausgegeben werden muss und dieses automatisch umgewandelt wird.

## HtmlDocController

Grundsätzlich besteht jede Seite einer Webseite aus einem einzigen HTML-Dokument. Aus diesem Grund kann anstatt eines einfachen Controllers auch ein `HtmlDocController` verwendet werden, welcher bereits über ein entsprechendes Attribut (`$this->htmldoc`) verfügt.

Durch die Verwendung des Controllers ist nicht mehr das vollständige HTML-Grundgerüst im Template erforderlich, sondern lediglich die HTML-Tags für den Body.

## Vorteile

Der große Vorteil an dieser Architektur besteht darin, dass das HTML-Dokument während der Verarbeitung jederzeit geändert werden kann. Dadurch können bestimmte HTML-Eigenschaften automatisch generiert werden.
