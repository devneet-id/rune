<?php

#NOTE: Main essence
$GLOBALS['CHANTER'] = true;


/* ARG
 * todo manipulation argument cli */

#NOTE: Stores full CLI argument string as a single line
$GLOBALS['CHANTER_ARG'] = implode(' ', $_SERVER['argv']);

#NOTE: Stores full CLI argument string as an array
$GLOBALS['CHANTER_ARGS'] = $_SERVER['argv'];

#NOTE: Holds parsed cast-related arguments from CLI
$GLOBALS['CHANTER_ARG_CAST'] = [];

#NOTE: Contains full list of separated CLI arguments
$GLOBALS['CHANTER_ARG_SPELL'] = [];

#NOTE: Contains full list of separated CLI arguments
$GLOBALS['CHANTER_ARG_LIST'] = [];



/* CAST
 * todo cast cli */

#NOTE: Stores all available cast definitions
$GLOBALS['CHANTER_CAST'] = [];

#NOTE: List of registered cast names or keys
$GLOBALS['CHANTER_CAST_LIST'] = [];



/* SPELL
 * todo spell mangement
 *  */
$GLOBALS['CHANTER_SPELL'] = [];



/* ECHO
 * todo set echo of the cast */

#NOTE: Holds text or data to be echoed after cast execution
$GLOBALS['CHANTER_ECHO'] = [];