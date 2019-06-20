<?php

    define("SERVIDOR","localhost");

    define("DATABASE","biblioteca");

    define("USERNAME","root");

    define("PASSWORD","acceso02");


    define("DSN","mysql:host=".SERVIDOR.";dbname=".DATABASE);
   //para encryptar
   const METHOD="AES-256-CBC";
   const SECRET_KEY='$BP@2017';// se puede cambiar para que laincriptacion sea unica
   const SECRET_IV='101712';// se puede cambiar para que laincriptacion sea unica

   ?>