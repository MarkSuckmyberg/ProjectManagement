<html>
<head>
  <title>Project Complexity and Risk Assessment</title>
</head>
<body>
<?php
require_once 'pdo.php';
$stm = $pdo->prepare("select * from question where section_num = 1");
$stm->execute();
?>
<h1>Section 1: Project Characteristics (18 Questions)</h1>
<form method="post" action="Strategic Management Risk.php">
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
               <input type="radio" name="question_<?php echo $choice->id; ?>"
               value="<?php echo $choice->id; ?>">
               <?php echo $choice->choice_content; ?>
             </ul>
           <?php } ?>
         </ol>
      </li>
  <?php } ?>
  </ul>
  <br>
  <input type ="submit" value="Submit">
</form>
<form action="StrategicManagementRisks.php">
  <input type="submit" value="Next Section">
</form>
</body>
</html>