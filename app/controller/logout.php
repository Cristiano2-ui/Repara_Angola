<?php
session_start();
session_unset();
session_destroy();
header("Location: /PROJECTO-RECICLA-FACIL");
exit;
