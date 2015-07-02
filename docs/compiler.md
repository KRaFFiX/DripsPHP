# Compiler

Um beispielsweise LESS- oder SCSS-Dateien kompilieren zu können, werden in Drips sogenannte Compiler eingesetzt. Ein Compiler muss das Interface `DripsPHP\Compiler\ICompiler` implementieren und hat somit eine Methode `compile($string)`.

## Beispiel LESS-Compiler

```php
<?php
// ...
$compiler = new LessCompiler();
$cssCode = $compiler->compile($lessCode);
// ...
```

## Beispiel SCSS-Compiler

```php
<?php
// ...
$compiler = new ScssCompiler();
$cssCode = $compiler->compile($scssCode);
// ...
```

> Um das Kompilieren zu vereinfachen, stellt Drips bereits vorgefertigte Controller zur Verfügung.
