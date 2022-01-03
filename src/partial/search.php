<?php
    /**
     * Create document from index: footballs
     */
    
    require_once("connect-es.php");

    $action = $_GET["action"] ?? "";

    $footballs = $client->indices()->exists(["index" => "footballs"]);
    $msg = $footballs ? "Hey you, footballs index exist!" : "Hey you, footballs index non-existent!";
    
    if (!$footballs) throw new Exception("footballs index not found!");

    $keyword = $_POST["keyword"] ?? null;
    if ($keyword != null) {
        // Search document index
        $params = [
            "index" => "footballs",
            "type" => "football_type",
            "body" => [
                "query" => [
                    "bool" => [
                        "should" => [
                            ["match" => ["fullname" => $keyword]],
                            ["match" => ["subject" => $keyword]],
                            ["match" => ["description" => $keyword]]
                        ]
                    ]
                ],
                "highlight" => [
                    "pre_tags" => ["<strong class='text-danger'>"],
                    "post_tags" => ["</strong>"],
                    "fields" => [
                        "description" => new stdClass(),
                    ]
                ]
            ]
        ];

        $req = $client->search($params);
        if (!$req) throw new Exception("No matching results!");
        
        $total = $req["hits"]["total"]["value"];
        $listFootballs = $req["hits"]["hits"];
    }

?>

<div class="card mt-3">
  <div class="card-body">
    <h5 class="card-title">Search Document Index</h5>
    <p class="card-text">
        <div class="alert alert-primary"><?= $msg ?></div>
    </p>
    <form action="" method="POST">
        <div class="form-group row">
            <label for="keyword" class="col-2">Keyword: </label>
            <input type="text" name="keyword" id="keyword" value="<?= $keyword ?>"/>
        </div>
        <div class="form-group row">
            <input type="submit" value="Submit"/>
        </div>
    </form>
  </div>
</div>

<?php if ($keyword && $total > 0) { ?>
    <table class="table table-dark">
    <thead>
        <tr>
        <th scope="col">#</th>
        <th scope="col">Fullname</th>
        <th scope="col">Age</th>
        <th scope="col">Subject</th>
        <th scope="col">Description</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listFootballs as $key => $value) { ?>
            <tr>
                <th scope="row"><?= $value["_id"] ?></th>
                <td><?= $value["_source"]["fullname"] ?></td>
                <td><?= $value["_source"]["age"] ?></td>
                <td><?= implode(",", $value["_source"]["subject"]) ?></td>
                <td><?= isset($value["highlight"]) ? implode(" // ", $value["highlight"]["description"]) : $value["_source"]["description"] ?></td>
            </tr>
        <?php } ?>
    </tbody>
    </table>
<?php } else { ?>
    <div>No matching results!</div>
<?php } ?>