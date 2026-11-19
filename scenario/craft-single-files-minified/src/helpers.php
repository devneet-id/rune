<?php
// a single php library: one file, required by an app
/* returns a greeting */
function craft_greet( $name ) {
  // trim the name first
  $name = trim( $name );
  return 'Hello, ' . $name;
}

define( 'CRAFT_LIBRARY', 'helpers' );
