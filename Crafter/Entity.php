<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */

function crafter() {
  return true;
}


/* HELPER
 * list all helper
 *  */
function crafter_reset() {
  global $CRAFTER_ITEM;
  global $CRAFTER_SEED;
  global $CRAFTER_SHARD;
  global $CRAFTER_SHARD_LIST;
  global $CRAFTER_SHARD_INJECTION;
  global $CRAFTER_SPARK;
  global $CRAFTER_SPARK_STATE;
  global $CRAFTER_SPARK_CLUSTER;
  global $CRAFTER_SPARK_DISTRIBUTE;

  $CRAFTER_ITEM = [];
  $CRAFTER_SEED = CRAFTER_RESET_SEED;
  $CRAFTER_SHARD = [];
  $CRAFTER_SHARD_LIST = [];
  $CRAFTER_SHARD_INJECTION = [];
  $CRAFTER_SPARK = CRAFTER_RESET_SPARK;
  $CRAFTER_SPARK_STATE = CRAFTER_RESET_SPARK_STATE;
  $CRAFTER_SPARK_CLUSTER = CRAFTER_RESET_SPARK_CLUSTER;
  $CRAFTER_SPARK_DISTRIBUTE = '';
}


/* SEED
 * todo set seed of the item
 * */
function crafter_seed_set( String $name, Mixed $value ) {
  global $CRAFTER_SEED;

  $CRAFTER_SEED[$name] = $value;
  
  aether_arcane("Crafter.entity.crafter_seed_set");
  return $value;
}
function crafter_seed_get( String $name ) {
  global $CRAFTER_SEED;

  aether_arcane("Crafter.entity.crafter_seed_get");
  return $CRAFTER_SEED[$name];
}


/* ITEM
 * todo crafter item 
 * */
function crafter_item_set( String $name, ?Callable $callable ) {
  global $CRAFTER_ITEM;

  $CRAFTER_ITEM[$name] = $callable;

  aether_arcane("Crafter.entity.crafter_item_set");
  return true;
}
function crafter_item_get( String $name ) {
  global $CRAFTER_ITEM;
  global $CRAFTER_SEED;
  global $CRAFTER_SHARD;
  global $CRAFTER_SPARK;
  global $CRAFTER_SPARK_STATE;
  
  if (isset($CRAFTER_ITEM[$name])) {
    
    $CRAFTER_ITEM[$name]();

    $CRAFTER_SPARK['item'] = $name;
    $CRAFTER_SPARK['seed'] = $CRAFTER_SEED;
    $CRAFTER_SPARK['shard'] = $CRAFTER_SHARD;

    $CRAFTER_SPARK_STATE['ready'] = true;
  }

  aether_arcane("Crafter.entity.crafter_item_get");
  return $CRAFTER_SPARK;
}


/* SHARD
 * todo get shard and crafting shards
 *  */
function crafter_shard_set( String $file_path, ?Callable $injection = NULL ) {
  global $CRAFTER_SHARD;
  global $CRAFTER_SHARD_LIST;
  global $CRAFTER_SHARD_INJECTION;

  if (file_exists($file_path)) {
    $CRAFTER_SHARD[$file_path] = forger_info($file_path);
    $CRAFTER_SHARD_LIST[] = $file_path;
    $CRAFTER_SHARD_INJECTION[$file_path] = $injection;
  }

  aether_arcane("Crafter.entity.crafter_shard_set");
  return $CRAFTER_SHARD;
}
function crafter_shard_get( String $file_path, ?Callable $injection = NULL ) {
  global $CRAFTER_SHARD;
  global $CRAFTER_SHARD_LIST;
  
  if (!isset($CRAFTER_SHARD_LIST[$file_path])) {
    crafter_shard_set($file_path, $injection);
  }
  
  aether_arcane("Crafter.entity.crafter_shard_get");
  return $CRAFTER_SHARD;
}



/* SPARK
 *  */
function crafter_spark( String $name, ?Callable $injection = NULL ) {
  global $CRAFTER_SHARD;
  global $CRAFTER_SHARD_INJECTION;
  global $CRAFTER_SHARD_LIST;
  global $CRAFTER_SPARK;
  global $CRAFTER_SPARK_STATE;
  global $CRAFTER_SPARK_CLUSTER;
  global $CRAFTER_SPARK_DISTRIBUTE;

  // state ready
  if (!$CRAFTER_SPARK_STATE['ready']) {
    crafter_item_get($name);
  }
  
  // sparkling process
  crafter_spark_clustering();
  crafter_spark_cleaning();
  crafter_spark_bundling();
  crafter_spark_distributing();

  // injection
  if (!empty($injection)) {
    $injection();
  }
  
  // keeper handling
  keeper_shard_clean();
  keeper_shard_set($CRAFTER_SHARD_LIST);

  // return
  aether_arcane("Crafter.entity.crafter_spark");
  return true;
}

function crafter_spark_message() {
  global $CRAFTER_SPARK;
  
  $name = $CRAFTER_SPARK['item'];
  $file_path = $CRAFTER_SPARK['seed']['DIST'];
  $file_size = aether_formatFileSize(filesize($CRAFTER_SPARK['seed']['DIST']));
  $total_shard = count($CRAFTER_SPARK['shard']);
  
  // aether_dd($CRAFTER_SPARK);
  whisper_echo("{{color-success}}{{icon-success}}{{label-success}}Crafting '$name' has been Sparked!!");
  whisper_echo("\n{{color-info}}{{icon-info}}{{label-info}}Path=$file_path, Size=$file_size, Shard=$total_shard");
}

function crafter_spark_clustering() {
  global $CRAFTER_SPARK;
  global $CRAFTER_SPARK_CLUSTER;
  global $CRAFTER_SHARD_INJECTION;

  foreach ($CRAFTER_SPARK['shard'] as $shard) {
    $source = forger_item($shard->target);

    // start shard injection
    if (isset($CRAFTER_SHARD_INJECTION[$shard->target])) {
      $inject = $CRAFTER_SHARD_INJECTION[$shard->target]( $source );
      if (!empty($inject)) {
        $source = $inject;  
      }
    }
    
    // clustering by language
    if (strpos($shard->name, '.head')!==false) {
      $CRAFTER_SPARK_CLUSTER['head.html'][] = $source;
    }else {
      $CRAFTER_SPARK_CLUSTER[$shard->ext][] = $source;
    }
  }

  aether_arcane("Crafter.entity.crafter_spark_clustering");
  return true;
}

function crafter_spark_cleaning() {
  global $CRAFTER_SPARK_CLUSTER;

  $recluster = [];
  foreach ($CRAFTER_SPARK_CLUSTER as $lang => $items) {
    foreach ($items as $item) {
      $cleaner = CRAFTER_CLEANING[$lang];

      foreach ($cleaner as $row) {
        $item = str_ireplace($row, '', $item);
      }

      $recluster[$lang][] = trim($item);
    }
  }

  $CRAFTER_SPARK_CLUSTER = $recluster;
  aether_arcane("Crafter.entity.crafter_spark_cleaning");
  return true;
}

function crafter_spark_bundling() {
  global $CRAFTER_SPARK;
  global $CRAFTER_SPARK_DISTRIBUTE;
  global $CRAFTER_SPARK_CLUSTER;

  $seed_language = $CRAFTER_SPARK['seed']['LANGUAGE'];
  $seed_type = $CRAFTER_SPARK['seed']['TYPE'];
  $seed_encryption = $CRAFTER_SPARK['seed']['ENCRYPTION'];
  
  $encoding = $seed_encryption . '_encode';
  $weaver_selected = 0;
  
  foreach (CRAFTER_WEAVER as $CWID => $weaver) {
    if ($weaver[0] == $seed_language && $weaver[1] == $seed_type) {
      $templates = weaver_item($weaver[3]);
      $weaver_selected = $CWID;
    }
  }

  // copyright
  $templates = weaver_bind($templates, 'COPYRIGHT', AETHER_COPYRIGHT);
  
  // class type
  if (CRAFTER_WEAVER[$weaver_selected][2] == 2) {
    $templates = weaver_bind($templates, 'ENCRYPTION', $seed_encryption);
    
    $plain = weaver_item(CRAFTER_WEAVER[0][3]);
    $templates = weaver_bind($templates, 'CONSTRUCT', $encoding($plain));
    $templates = weaver_bind($templates, 'COPYRIGHT', AETHER_COPYRIGHT);
  }

  // cluster
  foreach ($CRAFTER_SPARK_CLUSTER as $type => $cluster) {
    $search = CRAFTER_VARIABLE[$type];
    $source = implode("\n", $cluster);
    
    if (CRAFTER_WEAVER[$weaver_selected][2] == 2) {
      $source = $encoding($source);
    }
    
    $templates = weaver_bind($templates, $search, $source);
  }

  // hash
  $templates = weaver_bind($templates, 'HASH-APP', cipher_id());
  
  $CRAFTER_SPARK_DISTRIBUTE = $templates;
  
  aether_arcane("Crafter.entity.crafter_spark_bundling");
  return true;
}

function crafter_spark_distributing() {
  global $CRAFTER_SEED;
  global $CRAFTER_SPARK_DISTRIBUTE;

  $dist = $CRAFTER_SEED['DIST'];
  forger_fix(forger_trace($dist));
  forger_item($dist, $CRAFTER_SPARK_DISTRIBUTE);

  aether_arcane("Crafter.entity.crafter_spark_distributing");
  return true;
}