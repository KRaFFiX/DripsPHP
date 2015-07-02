# Pluginsystem

Um das Framework erweitern zu können, werden sogenannte Plugins verwendet. Plugins müssen grundsätzlich im Verzeichnis `plugins` abgelegt werden. Außerdem kann das jeweilige Plugin nur vom ClassLoader geladen werden, wenn das Plugin auch aktiviert ist.

Jedes Plugin muss über eine `Plugin.php`-Datei verfügen. Die darin enthaltene Plugin-Klasse muss von der Klasse `DripsPHP\Plugin\BasePlugin` erben oder das Interface `DripsPHP\Plugin\IPlugin` implementieren.

Mit Hilfe von Konstanten werden wichtige Informationen über das Plugin gespeichert:

### Beispiel (Plugin-Klasse)

```php
<?php
namespace plugins\testPlugin;

use DripsPHP\Plugin\BasePlugin;

class Plugin extends BasePlugin
{
    const AUTHOR = 'Name des Autors';
    const NAME = 'Name des Plugins';
    const DESC = 'Beschreibung des Plugins';
    const VERSION = '0.1';
    const ID = 'eindeutige ID des Plugins - für Updates zum Beispiel';

    public function __construct(){
        // ... do something
    }
}

```

Plugins können über das CLI von Drips aktiviert und deaktiviert werden. Ist ein Plugin aktiviert, so wird beim Start von Drips automatisch ein neues Objekt der `Plugin`-Klasse eines Plugins erstellt. Somit gibt der Konstruktor der Klasse vor, was zu tun ist.

Möglicherweise will ein Plugin ein CMD für das DripsCLI hinzufügen. In diesem Fall muss das CMD im Konstruktor des Plugins registriert werden.

### Beispiel (CMD)

```php
<?php
// ...
CMD::register('TestCMD', '\\plugins\\testPlugin\\TestCMD');
// ...
```

> Der erste Parameter gibt den Namen des CMDs an und der 2. Parameter gibt den vollständigen Klassenamen (+Namespace) des CMDs an.

Genauso können auch ViewPlugins registriert werden.

### Beispiel (ViewPlugin)

```php
<?php
// ...
ViewPlugin::register('TestViewPlugin', '\\plugins\\testPlugin\\TestViewPlugin');
// ...
```
