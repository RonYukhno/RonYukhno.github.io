<?php

	mysql_connect("db1.ho.ua", "citrus", "toor123")
    or die("<p>Ошибка подключения к базе данных! " . mysql_error() . "</p>");
    mysql_select_db("citrus")
    or die("<p>Ошибка выбора базы данных! ". mysql_error() . "</p>");

?>