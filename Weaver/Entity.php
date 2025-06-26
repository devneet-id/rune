<?php


function weaver() {
  return true;
}

// function weaver_load( $source ) {
//   return file_get_contents($source);
// }


/* BIND
 * todo binding string
 *  */
function weaver_bind( $template, $search, $data ) {
  $template = (!empty($template)) ? $template : '';
  $search = (!empty($search)) ? $search : '';
  $data = (!empty($data)) ? $data : '';

  $template = str_replace( strtolower("{{ ".$search." }}"), $data, $template);
  $template = str_replace( strtolower("{{".$search."}}"), $data, $template);
  $template = str_replace( strtoupper("{{ ".$search." }}"), $data, $template);
  $template = str_replace( strtoupper("{{".$search."}}"), $data, $template);

  aether_arcane('Weaver.entity.weaver_bind');
  return $template;
}

function weaver_bind_extract( $value ) {
  // get the {{var}}
  $matches = [];
  preg_match_all('/{{(.*?)}}/', $value, $matches);
  $vars = array_values($matches[1]);

  aether_arcane('Weaver.entity.weaver_bind_extract');
  return $vars;
}

function weaver_bind_multiple( $template, $datas ) {
  foreach ($datas as $search => $value) {
    $template = weaver_bind($template, $search, $value);
  }

  aether_arcane('Weaver.entity.weaver_bind_multiple');
  return $template;
}

// deprecated change to str_replace()
function weaver_bind_custom( $template, $search, $data ) {
  $search = $search;
  $text = str_replace($search, $data, $template);
  return $text;
}




/* ITEM
 * todo get, set & stacking item
 *  */
function weaver_item( $source ) {
  $item = file_get_contents($source);

  return $item;
}



function weaver_wrap_echo($text, $wrap, $divider) {
    $lines = wordwrap($text, $wrap, "\n"); // Wrap dengan newline
    $wrapped = explode("\n", $lines);      // Pecah jadi array per baris

    // Tambahkan divider di depan tiap baris
    foreach ($wrapped as &$line) {
        $line = $divider . $line;
    }

    return implode("\n", $wrapped); // Gabungkan lagi jadi string utuh
  }




/* WEBS
 * 
 * */
function weaver_min( $input, $type='html' ) {
  $type = strtolower($type);
  if($type == 'html') return weaver_min_html( $input );
  elseif($type == 'css') return weaver_min_css( $input );
  elseif($type == 'js') return weaver_min_js( $input );
  elseif($type == 'php') return weaver_min_php( $input );
  else return $input;
}

function weaver_min_css( $input ) {
  if(trim($input) === "") return $input;
  return preg_replace(
    array(
      // Remove comment(s)
      '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
      // Remove unused white-space(s)
      '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
      // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
      '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
      // Replace `:0 0 0 0` with `:0`
      '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
      // Replace `background-position:0` with `background-position:0 0`
      '#(background-position):0(?=[;\}])#si',
      // Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
      '#(?<=[\s:,\-])0+\.(\d+)#s',
      // Minify string value
      '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
      '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
      // Minify HEX color code
      '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
      // Replace `(border|outline):none` with `(border|outline):0`
      '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
      // Remove empty selector(s)
      '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
    ),
    array(
      '$1',
      '$1$2$3$4$5$6$7',
      '$1',
      ':0',
      '$1:0 0',
      '.$1',
      '$1$3',
      '$1$2$4$5',
      '$1$2$3',
      '$1:0',
      '$1$2'
    ),
  $input);
}

function weaver_min_js( $input ) {
  if(trim($input) === "") return $input;

  // Ekspresi reguler untuk menghapus komentar, termasuk dalam string dan regex
  $removeComments = '#\s*("(?:[^"\\\\]++|\\\\.)*+"|\'(?:[^\'\\\\]++|\\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#';

  // Ekspresi reguler untuk menghapus spasi putih di luar string dan regex
  $removeWhiteSpace = '#("(?:[^"\\\\]++|\\\\.)*+"|\'(?:[^\'\\\\]++|\\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*()\\-=+\\[\\]{}|;:,.<>?\/])\s*#s';

  // Ekspresi reguler untuk menghapus tanda kurung kurawal terakhir
  $removeLastSemicolon = '#;+\}#';

  // Ekspresi reguler untuk meminify atribut objek kecuali atribut JSON
  $minifyObjectAttributes = '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=:)#i';

  // Ekspresi reguler untuk mengganti akses objek seperti foo['bar'] menjadi foo.bar
  $replaceObjectAccess = '#([a-z0-9_)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i';

  // Array ekspresi reguler
  $patterns = array(
    $removeComments,
    $removeWhiteSpace,
    $removeLastSemicolon,
    $minifyObjectAttributes,
    $replaceObjectAccess
  );

  // Array penggantian untuk setiap ekspresi reguler
  $replacements = array(
    '$1',
    '$1$2',
    '}',
    '$1$3',
    '$1.$3'
  );

  // Terapkan semua ekspresi reguler pada input
  $minifiedCode = preg_replace($patterns, $replacements, $input);

  return $minifiedCode;
}

function weaver_min_html($input) {
  if (trim($input) === "") {
      return $input;
  }

  // Remove extra white-space(s) between HTML attribute(s)
  $input = preg_replace_callback(
      '#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s',
      function ($matches) {
          return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
      },
      str_replace("\r", "", $input)
  );

  // Minify HTML
  $input = preg_replace(
      array(
          // t = text
          // o = tag open
          // c = tag close
          // Keep important white-space(s) after self-closing HTML tag(s)
          '#<(img|input)(>| .*?>)#s',
          // Remove a line break and two or more white-space(s) between tag(s)
          '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
          '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
          '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
          '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
          '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
          '#<(img|input)(>| .*?>)<\/\1>#s', // reset previous fix
          '#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
          '#(?<=\>)(&nbsp;)(?=\<)#', // --ibid
          // Remove HTML comment(s) except IE comment(s)
          '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
      ),
      array(
          '<$1$2</$1>',
          '$1$2$3',
          '$1$2$3',
          '$1$2$3$4$5',
          '$1$2$3$4$5$6$7',
          '$1$2$3',
          '<$1$2',
          '$1 ',
          '$1',
          ""
      ),
      $input
  );

  // Remove remaining line breaks and tabs
  $input = preg_replace('/\n\s*\n/', "\n", $input); // Remove double newlines
  $input = preg_replace('/\t/', '', $input); // Remove tabs
  $input = preg_replace('/\s+/', ' ', $input); // Collapse multiple spaces into one
  $input = preg_replace('/\n\s*/', "\n", $input); // Remove line breaks followed by spaces

  // Trim any leading or trailing whitespace and newlines
  $input = trim($input);

  return $input;
}

function weaver_min_php($input) {
  // Hapus komentar baris (//) tanpa menghapus karakter lain di dalam string
  $input = preg_replace('/(?<!:|\'|")\/\/[^\n]*/', '', $input);

  // Hapus komentar blok (/* ... */) dengan hati-hati
  $input = preg_replace('/\/\*[\s\S]*?\*\//', '', $input);

  // Hapus spasi dan tab berlebih, tetapi biarkan spasi tunggal di antara kata-kata
  $input = preg_replace('/\s+/', ' ', $input);

  // Hapus spasi di sekitar tanda kurung, kurung kurawal, titik koma, dan koma
  $input = preg_replace('/\s*([\(\)\{\};,])\s*/', '$1', $input);

  // Hapus baris kosong yang mungkin tersisa
  $input = preg_replace('/\n\s*\n/', "\n", $input);

  // Hapus baris kosong di awal dan akhir
  $input = trim($input);

  return $input;
}