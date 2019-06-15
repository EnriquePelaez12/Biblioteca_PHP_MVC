<!--conexion a la base de datos-->
<?php
   const SERVE="";
   const DB="";
   const USER="";
   const PASS="";

   const SGBD="mysql:host=".SERVER.";dbname=".DB;
   
   //para encryptar
   const METHOD="AES-256-CBC";
   const SECRET_KEY='$BP@2017';// se puede cambiar para que laincriptacion sea unica
   const SECRET_IV='101712';// se puede cambiar para que laincriptacion sea unica