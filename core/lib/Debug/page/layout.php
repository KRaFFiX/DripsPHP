<?php
/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 31.01.15 - 17:15.
 */
require_once __DIR__.'/functions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>DripsPHP :: Debugger</title>
    <!-- jQuery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Highlight.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/styles/hybrid.min.css"/>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/highlight.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('pre code').each(function (i, block) {
                hljs.highlightBlock(block);
            });
        });
    </script>
    <style>
        <?php
        // load stylesheet
        include __DIR__.'/style.css';
        ?>
    </style>
</head>
<body>
    <h1 id="dp-debug-headline">DripsPHP-Debugger</h1>
    <?php
    $errors = $_ENV['DP_DEBUG'];
    foreach ($errors as $error) {
        echo "<div class='dp-debug-error'>";
        if ($error['isException']) {
            // Exception
            $exception = unserialize($error['context']);
            echo "<h2><span class='redError'>[".get_class($exception).']</span> '.$error['desc'].'</h1>';
            echo '<h3>'.$error['file'].'</h3>';
            echo '<pre><code>'.getCode($error['file'], $error['line']).'</code></pre>';
            echo '<h4>Line: '.$error['line'].'</h4>';
        } else {
            // Error
            echo "<h2><span class='redError'>[ERROR]</span> ".$error['desc'].'</h1>';
            echo '<h3>'.$error['file'].'</h3>';
            echo '<pre><code>'.getCode($error['file'], $error['line']).'</code></pre>';
            echo '<h4>Line: '.$error['line'].'</h4>';
        }
        echo '</div>';
    }
    ?>
</body>
</html>
