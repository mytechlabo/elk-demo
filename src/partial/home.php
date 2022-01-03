<?php
    /**
     * Create / Delete index: footballs
     */
    
    require_once("connect-es.php");

    $action = $_GET["action"] ?? "";

    $footballs = $client->indices()->exists(["index" => "footballs"]);
    $msg = $footballs ? "Hey you, footballs index exist!" : "Hey you, footballs index non-existent!";
    switch ($action) {
        case 'create':
            if ($footballs) break;

            $client->indices()->create(["index" => "footballs"]);
            break;
        case 'delete':
            if (!$footballs) break;

            $client->indices()->delete(["index" => "footballs"]);
            break;
    }

    $footballs = $client->indices()->exists(["index" => "footballs"]);
    $msg = $footballs ? "Hey you, footballs index exist!" : "Hey you, footballs index non-existent!";
?>

<div class="card mt-3">
  <div class="card-body">
    <h5 class="card-title">Manage Index</h5>
    <p class="card-text">
        <div class="alert alert-primary"><?= $msg ?></div>
    </p>
    <?php if (!$footballs) { ?>
        <a href="/?page=home&action=create" class="btn btn-success">Create INDEX</a>
    <?php } else { ?>
        <a href="/?page=home&action=delete" class="btn btn-danger">Delete INDEX</a>
    <?php } ?>
  </div>
</div>