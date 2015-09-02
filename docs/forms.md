# Formulare

Ein großes Ziel von Drips ist es den eigentlichen Programmcode, vom HTML-Code, also der eigentlichen Ausgabe separat zu speichern, um es nicht nur übersichtlicher sondern auch einfacher zu gestalten.

Aus diesem Grund werden Formulare in Drips mit Hilfe von PHP anstatt von HTML erzeugt. Dies erhöht natürlich zusätzlich die Sicherheit.

## Formulare anlegen

Um ein Formular erstellen zu können wird zunächst eine neue Klasse angelegt. Diese muss anschließend von `DripsPHP\Form\Form` erben. Nun kann die Klasse über folgende Methoden implementieren:

- `init()` - diese Methode wird zur Initialisierung des Formulars verwendet. Hier können die einzelnen Eingabefelder registriert werden.
- `submit()` - diese Methode wird automatisch aufgerufen sobald das Formular abgesendet und erfolgreich validiert wurde. (d.h. alle Eingaben auch gültig sind) Achtung: diese Methode muss ebenfalls `true` oder `false` zurück liefern.
- `validate()` - diese Methode kann verwendet werden um Eingaben manuell zu überprüfen. Bitte beachten Sie dabei, dass die Methode `true` oder `false` zurück geben muss.
- `render()` - diese Methode kann zur Ausgabe des Formulars verwendet werden. Die Methode wird automatisch aufgerufen sobald das Formular-Objekt ausgegeben werden soll.

### Beispiel

``` php
class EntityForm extends Form {
    public function init(){
        $entity = new Test();
        $this->bind($entity, array("property" => "property", "value" => "value"));

        $this->add(new Text("property"));
        $this->add(new Text(["name" => "value", "optional" => "optional"]));
        $this->add(new Button(["name" => "saveBtn", "value" => "Speichern"]));
    }
}
```

> Das Formular dient lediglich zur Demonstration und verfügt über keinen Nutzen. Aus dem Beispiel wird ersichtlich, dass ein Formular erzeugt wird, dass mit einem Entity verknüpft ist. Außerdem werden Eingabefelder hinzugefügt. 
> 
> Würde das Formular abgesendet werden, würden die Eingaben automatisch überprüft werden, bevor eine `submit()`-Methode aufgerufen werden würde. In dieser können anschließend Abarbeitung erfolgen.

## Inputs

Inputs sind Eingabefelder die ebenfalls mit PHP erzeugt werden, um sie dem Formular zuweisen zu können. Welche Inputs vorhanden sind kann in folgendem Verzeichnis nachgesehen werden: `core/lib/Form/Inputs`. Grundsätzlich funktioniert die Verwendung der Inputs immer gleich. Beim Anlegen eines Input-Objektes wird ein Array mitgegeben in dem alle Felder spezifiziert werden, wie sie es auch im HTML-Code werden (z.B.: name, value, ...). Die mitgegebenen Attribute werden automatisch später im HTML-Code angegeben, zusätzlich wird je nach Angabe automatisch ein Validator hinzugefügt, wie es beispielsweise bei einem Email-Input der Fall ist. Selbstverständlich können auch eigene Validator einem Input hinzugefügt werden.

## Entity-Form-Binding

In den meisten Fällen werden über Formulare Datenbank-Einträge geändert. Aus diesem Grund bietet Drips die Möglichkeit ein oder mehrere Entity/Entities mit einem Formular zu verknüpfen, sodass das Formular direkt die Felder des Entities ändern kann und das Entity einfach in der Datenbank aktualisiert werden kann.

### Beispiel

*Hinzufügen einer neuen Rolle bzw. Gruppe (zur Datenbank):*

``` php
class AddRoleForm extends Form
{
    protected $view;

    public function init()
    {
        $this->view = new View;

        $this->bind(new Role, array("name" => "name"));

        $this->add(new Text('name'));
        $this->add(new Button(['name' => 'addBtn', 'value' => App::$dictionary['roles']['add'], 'class' => 'btn']));

        static::on("invalid", function(){
            $this->view->assign('msg_failed', App::$dictionary['roles']['add_role_failed']);
        });
    }

    public function render()
    {
        $this->view->assign("cmsAddRoleForm", $this);
        $this->view->assign('html_title', App::$dictionary['roles']['add_role']);
        return $this->view->make('cms.views.users.addrole');
    }

    public function submit()
    {
        $role = $this->getEntities();
        if (Roles::insert($role)) {
            $this->view->assign('msg_success', App::$dictionary['roles']['add_role_success']);
            $this->reset();
            return true;
        }
        return false;
    }
}
```