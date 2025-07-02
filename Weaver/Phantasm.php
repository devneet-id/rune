<?php

namespace Rune\Weaver;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;
  
  public $version = '1.6';

  public $main = 'Weaver';

  public $user = 'Anwar Achilles';

  public $note = 'Built for advanced string manipulation, data binding, and template weaving—transforming raw data into structured text with flexible, context-aware logic.';

  public $link = [
    ['Aether', 'essence:entity', '1.15'],
    ['Crafter', 'ether:essence', '1.6'],
  ];

  public $node = [
    [
      'type' => 'ether',
      'call' => 'WEAVER',
      'note' => 'main ether',
    ],
    [
      'type' => 'essence',
      'call' => 'WEAVER',
      'note' => 'main essence',
    ],
    [
      'type' => 'essence',
      'call' => 'WEAVER_BOND',
      'note' => 'Global storage for bound template variables (key-value pairs)',
    ],
    [
      'type' => 'entity',
      'call' => 'weaver()',
      'note' => 'main entity',
    ],
    [
      'type' => 'entity',
      'call' => 'weaver_wrap_echo($text, $wrap, $divider)',
      'note' => 'Wrap and prefix text lines with a divider for formatted echo output',
    ],
    [
      'type' => 'entity',
      'call' => 'weaver_bind( $template, $search, String $data = "" )',
      'note' => 'Replace a variable placeholder in a template with given data',
    ],
    [
      'type' => 'entity',
      'call' => 'weaver_bind_extract( $value )',
      'note' => 'Extract all variable placeholders from a template string',
    ],
    [
      'type' => 'entity',
      'call' => 'weaver_bind_multiple( $template, $datas )',
      'note' => 'Bind multiple placeholders in a template using key-value data',
    ],
    [
      'type' => 'entity',
      'call' => 'weaver_bond_combine( $datas )',
      'note' => 'Merge and store new template data into the global weaver bond',
    ],
    [
      'type' => 'entity',
      'call' => 'weaver_bond_set( $key, $value )',
      'note' => 'Set a single key-value pair into the global weaver bond',
    ],
    [
      'type' => 'entity',
      'call' => 'weaver_bond_get( $key )',
      'note' => 'Get a stored value from the global weaver bond using its key',
    ],
    [
      'type' => 'entity',
      'call' => 'weaver_item( $source, $datas = [] )',
      'note' => 'Load and bind a template file with dynamic data replacements',
    ],
    [
      'type' => 'entity',
      'call' => 'weaver_item_set( $source, $alias = "" )',
      'note' => 'Load a template file and store it in memory, optionally using an alias',
    ],
    [
      'type' => 'entity',
      'call' => 'weaver_item_get( $find_source_alias )',
      'note' => 'Retrieve a stored template from memory by its alias or file path',
    ],
    [
      'type' => 'entity',
      'call' => 'weaver_min( $input, $type="html" )',
      'note' => 'Minify content by removing whitespace, comments, and unnecessary characters',
    ],
    [
      'type' => 'entity',
      'call' => 'weaver_min_css( $input )',
      'note' => 'Minify CSS content by removing whitespace, comments, and unnecessary characters',
    ],
    [
      'type' => 'entity',
      'call' => 'weaver_min_js( $input )',
      'note' => 'Minify JavaScript content by stripping out whitespace and simplifying expressions',
    ],
    [
      'type' => 'entity',
      'call' => 'weaver_min_html($input)',
      'note' => 'Minify HTML content by collapsing spaces and removing unnecessary tags or breaks',
    ],
    [
      'type' => 'entity',
      'call' => 'weaver_min_php($input)',
      'note' => 'Minify PHP code by trimming extra whitespace and cleaning up the syntax',
    ],
    [
      'type' => 'manifest',
      'call' => '_arise()',
      'note' => 'Optional lifecycle method for internal post-arise logic.',
    ],
    [
      'type' => 'manifest',
      'call' => '_aether_end()',
      'note' => 'Special hook for aether-based awakening phase, executed at the end of the crafter process.',
    ],
    [
      'type' => 'manifest',
      'call' => 'end()',
      'note' => 'Final phase of the class lifecycle, called after all manifest components are registered and ready.',
    ],
    [
      'type' => 'manifest',
      'call' => 'bind( String $template, $searchOrArray, String $data="" )',
      'note' => 'Bind one or multiple variables into a template string',
    ],
    [
      'type' => 'manifest',
      'call' => 'bond( String $key, Mixed $value )',
      'note' => 'Set or retrieve a key-value bond for template variable storage',
    ],
    [
      'type' => 'manifest',
      'call' => 'item( String $source, String $alias="" )',
      'note' => 'Load a template file into memory and optionally assign it an alias',
    ],
  ];

  public function awakening() {}
  
}