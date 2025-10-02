<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */


#NOTE: Main entity.
function crafter() {
  return true;
}


# ETC

#NOTE: Reset all Crafter globals to initial states using constants or empty values.
function crafter_reset() {
  global $CRAFTER_SEED;
  global $CRAFTER_SHARD;
  global $CRAFTER_SHARD_LIST;
  global $CRAFTER_SHARD_INJECTION;
  global $CRAFTER_SPARK;
  global $CRAFTER_SPARK_STATE;
  global $CRAFTER_SPARK_CLUSTER;
  global $CRAFTER_SPARK_DISTRIBUTE;

  $CRAFTER_SEED           = CRAFTER_RESET_SEED;
  $CRAFTER_SHARD          = [];
  $CRAFTER_SHARD_LIST     = [];
  $CRAFTER_SHARD_INJECTION = [];
  $CRAFTER_SPARK          = CRAFTER_RESET_SPARK;
  $CRAFTER_SPARK_STATE    = CRAFTER_RESET_SPARK_STATE;
  $CRAFTER_SPARK_CLUSTER  = CRAFTER_RESET_SPARK_CLUSTER;
  $CRAFTER_SPARK_DISTRIBUTE = '';
}



# SEED

#NOTE: Store a value into the global CRAFTER_SEED array using the given name as key.
function crafter_seed_set(String $name, Mixed $value) {
  global $CRAFTER_SEED;

  $CRAFTER_SEED[$name] = $value;

  aether_arcane("Crafter.entity.crafter_seed_set");
  return $value;
}

#NOTE: Get a value from the global CRAFTER_SEED array using the given name as key.
function crafter_seed_get(String $name) {
  global $CRAFTER_SEED;

  aether_arcane("Crafter.entity.crafter_seed_get");

  return $CRAFTER_SEED[$name];
}


# ITEM

#NOTE: Register a callable into the global CRAFTER_ITEM array using the given name as key.
function crafter_item_set(String $name, ?Callable $callable) {
  global $CRAFTER_ITEM;

  $CRAFTER_ITEM[$name] = $callable;

  aether_arcane("Crafter.entity.crafter_item_set");
  return true;
}

#NOTE: Execute a registered crafter item callable by name and update CRAFTER_SPARK with current state.
function crafter_item_get(String $name) {
  global $CRAFTER_ITEM;
  global $CRAFTER_SEED;
  global $CRAFTER_SHARD;
  global $CRAFTER_SPARK;
  global $CRAFTER_SPARK_STATE;

  if (isset($CRAFTER_ITEM[$name])) {
    // Execute the registered callable
    $CRAFTER_ITEM[$name]();

    // Update spark data with current seed and shard
    $CRAFTER_SPARK['item'] = $name;
    $CRAFTER_SPARK['seed'] = $CRAFTER_SEED;
    $CRAFTER_SPARK['shard'] = $CRAFTER_SHARD;

    // Mark spark state as ready
    $CRAFTER_SPARK_STATE = true;
  }

  aether_arcane("Crafter.entity.crafter_item_get");
  return $CRAFTER_SPARK;
}


# SHARD

#NOTE: Register a shard file with optional injection, and store its info and path in global shard arrays.
function crafter_shard_set(String $file_path, ?Callable $injection = NULL) {
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

#NOTE: Retrieve a shard file info, registering it first if not already listed.
function crafter_shard_get(String $file_path, ?Callable $injection = NULL) {
  global $CRAFTER_SHARD;
  global $CRAFTER_SHARD_LIST;

  if (!in_array($file_path, $CRAFTER_SHARD_LIST)) {
    crafter_shard_set($file_path, $injection);
  }

  aether_arcane("Crafter.entity.crafter_shard_get");
  return $CRAFTER_SHARD;
}


# SPARK

#NOTE: Run full crafting pipeline — validate readiness, process shards, apply injection, and finalize crafting result.
function crafter_spark(String $name, ?Callable $injection = NULL) {
  global $CRAFTER_SHARD;
  global $CRAFTER_SHARD_INJECTION;
  global $CRAFTER_SHARD_LIST;
  global $CRAFTER_SPARK;
  global $CRAFTER_SPARK_STATE;
  global $CRAFTER_SPARK_CLUSTER;
  global $CRAFTER_SPARK_DISTRIBUTE;

  // Ensure the spark state is ready
  if (!$CRAFTER_SPARK_STATE) {
    crafter_item_get($name);
  }

  // Core crafting steps
  crafter_spark_clustering();
  crafter_spark_cleaning();
  crafter_spark_bundling();
  
  // Optional user-defined injection
  if (!empty($injection)) {
    $injection();
  }

  crafter_spark_distributing();


  // Optional keeper logic (currently disabled)
  // keeper_shard_clean();
  // keeper_shard_set($CRAFTER_SHARD_LIST);

  // Show result message and reset
  crafter_spark_message();
  crafter_reset();

  aether_arcane("Crafter.entity.crafter_spark");
  return true;
}

#NOTE: Display crafting summary with item name, output path, file size, and total shards used.
function crafter_spark_message() {
  global $CRAFTER_SPARK;

  $name        = $CRAFTER_SPARK['item'];
  $file_path   = $CRAFTER_SPARK['seed']['DIST'];
  $file_size   = aether_formatFileSize(filesize($file_path));
  $total_shard = count($CRAFTER_SPARK['shard']);

  // Display crafting success message
  whisper_echo("{{color-success}}{{icon-success}}{{color-end}} Crafting {{color-success}}$name{{color-end}} has been Sparked!!\n");
  whisper_echo("{{color-info}}{{icon-info}}{{label-info}}{{color-end}}Path={{color-info}}$file_path{{color-end}}, Size={{color-info}}$file_size{{color-end}}, Shard={{color-info}}$total_shard{{color-end}}\n");
}

#NOTE: Inject and group each shard’s content into the appropriate cluster based on name or file type.
function crafter_spark_clustering() {
  global $CRAFTER_SPARK;
  global $CRAFTER_SPARK_CLUSTER;
  global $CRAFTER_SHARD_INJECTION;

  foreach ($CRAFTER_SPARK['shard'] as $shard) {
    $source = forger_item($shard->target);

    // Run custom injection if available
    if (isset($CRAFTER_SHARD_INJECTION[$shard->target])) {
      $inject = $CRAFTER_SHARD_INJECTION[$shard->target]($source);
      if (!empty($inject)) {
        $source = $inject;
      }
    }

    // Cluster based on file naming or extension
    if (strpos($shard->name, '.head') !== false) {
      $CRAFTER_SPARK_CLUSTER['head.html'][] = $source;
    } else {
      $CRAFTER_SPARK_CLUSTER[$shard->ext][] = $source;
    }
  }

  aether_arcane("Crafter.entity.crafter_spark_clustering");
  return true;
}

#NOTE: Clean each cluster by removing unwanted patterns using language-specific cleaning rules.
function crafter_spark_cleaning() {
  global $CRAFTER_SPARK_CLUSTER;
  global $CRAFTER_SEED;

  $recluster = [];

  foreach ($CRAFTER_SPARK_CLUSTER as $lang => $items) {
    foreach ($items as $item) {
      $cleaner = CRAFTER_CLEANING[$lang];

      foreach ($cleaner as $row) {
        $item = str_ireplace($row, '', $item);
      }

      // Minify code
      if (in_array($lang, $CRAFTER_SEED['MINIFIED'])) {
        $item = weaver_min($item, $lang);
      }

      $recluster[$lang][] = trim($item);
    }
  }

  $CRAFTER_SPARK_CLUSTER = $recluster;

  aether_arcane("Crafter.entity.crafter_spark_cleaning");
  return true;
}

#NOTE: Bundle cleaned shard clusters and metadata into a final distributable template.
function crafter_spark_bundling() {
  global $CRAFTER_SPARK;
  global $CRAFTER_SPARK_DISTRIBUTE;
  global $CRAFTER_SPARK_CLUSTER;

  $seed_language   = $CRAFTER_SPARK['seed']['LANGUAGE'];
  $seed_type       = $CRAFTER_SPARK['seed']['TYPE'];
  $seed_encryption = $CRAFTER_SPARK['seed']['ENCRYPTION'];

  $encoding = $seed_encryption . '_encode';
  $weaver_selected = 0;

  // Select the appropriate weaver based on language and type
  foreach (CRAFTER_WEAVER as $CWID => $weaver) {
    if ($weaver[0] == $seed_language && $weaver[1] == $seed_type) {
      $templates = weaver_item($weaver[3]);
      $weaver_selected = $CWID;
    }
  }

  // Inject copyright
  $templates = weaver_bind($templates, 'COPYRIGHT', AETHER_COPYRIGHT);

  // If class type is 2, apply encryption and constructor
  if (CRAFTER_WEAVER[$weaver_selected][2] == 2) {
    $templates = weaver_bind($templates, 'ENCRYPTION', $seed_encryption);

    $plain = weaver_item(CRAFTER_WEAVER[0][3]);
    $templates = weaver_bind($templates, 'CONSTRUCT', $encoding($plain));
    $templates = weaver_bind($templates, 'COPYRIGHT', AETHER_COPYRIGHT); // Reinjection
  }

  // Bind all clusters to the template
  foreach ($CRAFTER_SPARK_CLUSTER as $type => $cluster) {
    $search = CRAFTER_VARIABLE[$type];
    $source = implode("\n", $cluster);

    if (CRAFTER_WEAVER[$weaver_selected][2] == 2) {
      $source = $encoding($source);
    }

    $templates = weaver_bind($templates, $search, $source);
  }

  // Inject application hash
  $templates = weaver_bind($templates, 'HASH-APP', cipher_id());

  // Save final bundled result
  $CRAFTER_SPARK_DISTRIBUTE = $templates;

  aether_arcane("Crafter.entity.crafter_spark_bundling");
  return true;
}

#NOTE: Write the bundled template to the distribution path defined in crafter seed.
function crafter_spark_distributing() {
  global $CRAFTER_SEED;
  global $CRAFTER_SPARK_DISTRIBUTE;
  global $FORGER_KEEPER_SHARD;
  
  $FORGER_KEEPER_SHARD = false;

  $dist = $CRAFTER_SEED['DIST'];
  forger_fix(forger_trace($dist));
  forger_item($dist, $CRAFTER_SPARK_DISTRIBUTE);

  aether_arcane("Crafter.entity.crafter_spark_distributing");
  return true;
}
