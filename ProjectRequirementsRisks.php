<?php

session_start();
if(!$_SESSION["loggedin"]){
  $_SESSION["message"] = "Please login first";
  header("Location: login.php");
  exit();
}

require_once 'pdo.php';
$stm = $pdo->prepare("select * from question where section_num = 7");
$stm->execute();
array_push($_SESSION["totalWeight"], 0);
$_SESSION["totalWeight"][6] = 0;

if(isset($_POST["submit"])){

  for($i=50 ; $i<=64 ; $i++){

    if(!isset($_POST["question_".$i]) || $_POST["question_".$i] == ""){
      $_SESSION["message"] = "Please do not leave any fields empty";
      header("Location: ProjectRequirementsRisks.php");
      exit();
    }

    $_SESSION["totalWeight"][6] = $_SESSION["totalWeight"][6] + $_POST["question_".$i];

  }

  $_SESSION["message"] = "";

  $total = 0;
  for($i=0 ; $i<count($_SESSION["totalWeight"]) ; $i++){
    $total = $total + $_SESSION["totalWeight"][$i];
  }

  $rating = ($total/320)*100;
  $complexity = "";

  if($rating<45){
    $complexity = "Sustaining";
  }else if($rating>=45 || $rating<=63){
    $complexity = "Tactical";
  }else if($rating>=64 || $rating<=82){
    $complexity = "Evolutionary";
  }else if($rating>82){
    $complexity = "Transformational";
  }

  array_push($_SESSION["newProj"], $complexity);
  array_push($_SESSION["newProj"], $rating);

  $autoInfo = 'INSERT INTO project
  (name, owner, financial, duration, mode, complexity, rating) VALUES (:n, :ow, :fn, :dr, :md, :com, :rat)';
  $stmt = $pdo->prepare($autoInfo);
  $stmt->execute(array(
    ':n' => $_SESSION["newProj"][0],
    ':ow' => $_SESSION["newProj"][1],
    ':fn' => $_SESSION["newProj"][2],
    ':dr' => $_SESSION["newProj"][3],
    ':md' => $_SESSION["newProj"][4],
    ':com' => $_SESSION["newProj"][5],
    ':rat' => $_SESSION["newProj"][6])
  );

  header("Location: registrationDisplay.php");
  exit();

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Project Requirements Risks</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link rel="stylesheet" href="qStyles.css">
  <style>

  body{
    background-color: #ebfeff;
  }
  .navbar {
    padding-top: 5px;
    margin-bottom: 0%;
    border-radius: 0;
    background-color: #1B1B1B;
  }

  .container:hover .overlay {
    opacity: 1;
  }

  .text {
    color: white;
    font-size: 20px;
    position: absolute;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    text-align: center;
  }

  .sidenav {
    width: 300px;
      height: 100%;
    }
    .intopositionBoyz{
      padding-right: 25px;
    }
    footer {
      padding: 15px;
      position: relative;
      left: 0;
      bottom: 0;
      width: 100%;
      text-align: center;
      background-color: #343a42;
    }
  </style>

</head>
<body>

  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="mainMenu.php" style="color:#FFFFFF;">Project Management System</a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav navbar-right">

<form method="post">

        </form>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container text-center downBro">
<h1>Section 7: Project Requirements Risks (15 Questions)</h1>
  </div>

<h2 style="color:red;margin-left: 40px;"><?php echo $_SESSION["message"] ?></h2>
<form method="post">
<ul style="list-style-type:none;">
  <?php while ($question = $stm->fetch(PDO::FETCH_OBJ)){?>
      <li>
        <?php echo $question->question_content; ?>
        <input type="hidden" name="questionId" value="<?php echo $question->id; ?>"
        <ol type="a">
          <?php
          $stm2 = $pdo->prepare("select * from choice where question_id = :question_id");
          $stm2->bindValue("question_id", $question->id);
          $stm2->execute();
           ?>
           <?php while ($choice = $stm2->fetch(PDO::FETCH_OBJ)){?>
             <ul>
               <input type="radio" name="question_<?php echo $question->id; ?>"
               value="<?php echo $choice->weight; ?>">
               <?php echo $choice->choice_content; ?>
             </ul>
           <?php } ?>
         </ol>
      </li>
  <?php } ?>
  </ul>
  <br>
  <div class="container intopositionBoyz">
  <input type ="submit" value="Submit and Finish" class="btn btn-success btn-lg " name="submit">
  <input type ="reset" value="Reset" class="btn btn-secondary btn-lg ">
  </div>


</form>

<footer class="container-fluid text-left">
  <button onclick="goBack()" class="btn btn-primary btn-lg">Back</button>
  <script>
    function goBack() {
      window.location.replace("ProjectManagementIntegrationRisks.php");
    }
  </script>
</footer>

</body>
</html>
