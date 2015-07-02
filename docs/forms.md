# Formulare

Ein großes Ziel von Drips ist es den eigentlichen Programmcode, vom HTML-Code, also der eigentlich Ausgabe seperat zu speichern, um es nicht nur übersichtlicher sondern auch einfacher zu gestalten.
Aus diesem Grund werden Formulare in Drips mit Hilfe von PHP anstatt von HTML erzeugt. Dies erhöht natürlich zusätzlich die Sicherheit.

## Formulare anlegen

Um ein Formular anlegen zu können wird die `DripsPHP\HTML\Form`-Klasse verwendet. Letzten Endes übernimmt diese Klasse nicht nur die Ausgabe bzw. Generierung des entsprechenden HTML-Codes sondern gleichzeitig die Validierung der Eingabefelder mit Hilfe der Validators.

## Beispiel Kontakt-Formular

**PHP-Datei (Controller):**

```php
<?php
// ...
$form = Form::get('testContactForm');
if ($form->submitted()) {
    $error = '';
    if ($form->isValid()) {
        $this->view->assign('msg', 'Formular wurde erfolgreich abgesendet.');
        $form->reset();
    } else {
        if (!$form->getInput('email')->isValid()) {
            $error .= 'Die eingegebene Email-Adresse ist nicht korrekt. ';
        }
        if (!$form->getInput('subject')->isValid()) {
            $error .= 'Sie m&uuml;ssen einen Betreff angeben. ';
        }
        if (!$form->getInput('message')->isValid()) {
            $error .= 'Sie m&uuml;ssen eine Nachricht angeben. ';
        }
        $this->view->assign('msg', $error);
    }
}

$form = new Form('testContactForm');
$form->add(new Text(['name' => 'subject', 'required' => 'required']));
$form->add(new Email('email'));
$form->add(new Textarea(['name' => 'message', 'required' => 'required']));
$form->add(new Button(['name' => 'sendBtn', 'value' => 'Absenden']));
$this->view->assign('testContactForm', $form);

return $this->view->make('test.views.contact');
// ...
```

**Template-Datei:**

```html
<h2>Contact</h2>
<!-- testContactForm.open() -->
{{{ msg }}}
<table>
    <tr>
        <td>Betreff:</td>
        <td>{{{ testContactForm.subject }}}</td>
    </tr>
    <tr>
        <td>Email:</td>
        <td>{{{ testContactForm.email }}}</td>
    </tr>
    <tr>
        <td>Nachricht:</td>
        <td>{{{ testContactForm.message }}}</td>
    </tr>
    <tr>
        <td colspan="2">{{{ testContactForm.sendBtn }}}</td>
    </tr>
</table>
<br/>
<!-- testContactForm.close() -->
```

> Das Beispiel zeigt einerseits die Erstellung eines Formulars mittels PHP und anschließende Auswertung. Die Eingabeprüfung erfolgt mit Hilfe von Validators der entsprechenden Eingabefelder.

> Da das Formular an das Template übergeben wird, kann im Template auf das Formular zugegriffen werden.

## Inputs

Inputs sind Eingabefelder die ebenfalls mit PHP erzeugt werden, um sie dem Formular zuweisen zu können. Welche Inputs vorhanden sind kann in folgendem Verzeichnis nachgesehen werden: `core/lib/HTML/Inputs`. Grundsätzlich funktioniert die Verwendung der Inputs immer gleich. Beim Anlegen eines Input-Objektes wird ein Array mitgegeben in dem alle Felder spezifiziert werden, wie sie es auch im HTML-Code werden (z.B.: name, value, ...). Die mitgegebenen Attribute werden automatisch später im HTML-Code angegeben, zusätzlich wird je nach Angabe automatisch ein Validator hinzugefügt, wie es beispielsweise bei einem Email-Input der Fall ist. Selbstverständlich können auch eigene Validator einem Input hinzugefügt werden.

## Entity-Form-Binding

In den meisten Fällen werden über Formulare Datenbank-Einträge geändert. Aus diesem Grund bietet Drips die Möglichkeit ein Entity mit einem Formular zu verknüpfen, sodass das Formular direkt die Felder des Entities ändern kann und das Entity einfach in der Datenbank aktualisiert werden kann.

```php
<?php
// ...
$form = Form::get('userForm');
if ($form->submitted() && $form->isValid()) {
    $user = $form->getEntity();
    // das User-Objekt könnte nun in der Datenbank aktualisiert werden
    echo $user;
    $form->reset();
}

// User mit der ID = 1
$user = Users::get(1);

$userForm = new Form('userForm');
echo $userForm->open();
$userForm->from($user, ['username' => 'username', 'email' => 'email']);
echo $userForm->add(new Text("username"));
echo $userForm->add(new Text("email"));
echo $userForm->add(new Button(['name' => 'Submit', 'value' => 'Speichern']));
echo $userForm->close();
// ...
```
