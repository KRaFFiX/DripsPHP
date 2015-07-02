<?php

/*
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 24.05.15 - 10:50.
 */

use DripsPHP\App;
use DripsPHP\Plugin\PluginHandler;

PluginHandler::load();
App::on('shutdown', function () {
    PluginHandler::save();
});
