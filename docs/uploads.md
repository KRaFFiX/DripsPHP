# Uploader

Der Datei-Upload in PHP ist relativ umständlich und es sind oftmals viele Überprüfungsschritte möglich. Diese Überprüfungen, wie etwa die Dateigröße sind allerdings immer die gleichen. Aus diesem Grund stellt das Drips-Framework eine Uploader-Klasse zur Verfügung.

## Beispiel

```php
<?php
namespace package\controllers;

use DripsPHP\MVC\Controller;
use DripsPHP\HTML\Form;
use DripsPHP\HTML\Inputs\File;
use DripsPHP\HTML\Inputs\Button;
use DripsPHP\Routing\Redirect;
use DripsPHP\Uploader\Uploader;

class UploaderController extends Controller
{
    public function get()
    {
        $this->form();
    }

    public function post()
    {
        $form = Form::get("uploadTest");
        if ($form->submitted()) {
            $uploader = new Uploader();
            $uploader->setFiletypes("png");
            if ($uploader->upload("uploadFile", "core/tmp")) {
                echo "Upload erfolgreich!";
            } else {
                echo "Upload fehlgeschlagen!";
            }
        }
    }

    public function form()
    {
        $form = new Form("uploadTest", "", "POST", true);
        echo $form->open();
        echo $form->add(new File(["name" => "uploadFile"]));
        echo $form->add(new Button(["name" => "uploadBtn", "value" => "Upload"]));
        echo $form->close();
    }
}
```
