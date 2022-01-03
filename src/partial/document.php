<?php
    /**
     * Create document from index: footballs
     */
    
    require_once("connect-es.php");

    $action = $_GET["action"] ?? "";

    $footballs = $client->indices()->exists(["index" => "footballs"]);
    $msg = $footballs ? "Hey you, footballs index exist!" : "Hey you, footballs index non-existent!";
    
    if (!$footballs) throw new Exception("footballs index not found!");

    $id = $_POST["id"] ?? null;
    $fullname = $_POST["fullname"] ?? null;
    $age = $_POST["age"] ?? null;
    $subject = $_POST["subject"] ?? null;
    $description = $_POST["description"] ?? null;

    if ($id != null && $fullname != null && $age != null && $subject != null && $description != null) {
        // Create or Update document from footballs index
        $params = [
            "index" => "footballs",
            "type" => "football_type",
            "id" => $id,
            "body" => [
                "fullname" => $fullname,
                "age" => $age,
                "subject" => explode(",", $subject),
                "description" => $description,
            ]
        ];

        $req = $client->index($params);
        if (!$req) throw new Exception("footballs index fault!");

        $msg = "Hey you, footballs index sucessfull: $id";
        $id = $fullname = $age = $subject = $description = null;
    }

?>

<div class="card mt-3">
  <div class="card-body">
    <h5 class="card-title">Document Index</h5>
    <p class="card-text">
        <div class="alert alert-primary"><?= $msg ?></div>
    </p>
    <form action="" method="POST">
        <div class="form-group row">
            <label for="id" class="col-2">Id: </label>
            <input type="text" name="id" id="id" value="<?= $id ?>"/>
        </div>
        <div class="form-group row">
            <label for="fullname" class="col-2">Fullname: </label>
            <input type="text" name="fullname" id="fullname" value="<?= $fullname ?>"/>
        </div>
        <div class="form-group row">
            <label for="age" class="col-2">Age: </label>
            <input type="text" name="age" id="age" value="<?= $age ?>"/>
        </div>
        <div class="form-group row">
            <label for="subject" class="col-2">Subject: </label>
            <textarea name="subject" id="subject" value="<?= $subject ?>"></textarea>
        </div>
        <div class="form-group row">
            <label for="description" class="col-2">Description: </label>
            <textarea name="description" id="description" value="<?= $description ?>"></textarea>
        </div>
        <div class="form-group row">
            <input type="submit" value="Submit"/>
        </div>
    </form>
  </div>
</div>