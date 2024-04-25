<?php

session_start();
//echo $_SESSION["mp3"];
unlink($_SESSION["mp3"]);