<?php

    require_once 'db_config.php';
    
    session_start();
	ob_start();

    $user=$_SESSION["user_login"];
    $last_id=$_GET['last_id'];
    $sql=$db->prepare("SELECT * FROM not_defterleri WHERE not_defteri_id < :last_id AND kullanici_id=:k_id Order By not_defteri_id DESC Limit 6");
    $sql->execute(array(':k_id'=>$user,':last_id'=>$last_id));
    while($row=$sql->fetch(PDO::FETCH_ASSOC)){

?>

<div class="col-md-6 col-lg-4 not_defteri_id mb-3" id="<?php echo $row['not_defteri_id'] ?>">
                    <div class="card defter">
                        <div class="title shadow rounded p-1 ">
                            <img src="images/white.png" class="card-img-top">
                            <a  href="notes.php?not_defteri_id=<?php echo $row['not_defteri_id'] ?>"><div class="text-dark card-img-overlay d-flex align-items-center justify-content-center">
                            <h3><?php echo $row['not_defteri_ad'] ?></h3></div></a>
                        </div>
                        <div class="card-body shadow">
                            <h5 class="card-title">
                                <?php
                                    echo $row['not_defteri_ad'];
                                ?>
                            </h5>
                            <p>
                                <?php
                                    $not_defteri=$row['not_defteri_id'];
                                    //$stmt= $db->prepare("SELECT * FROM notlar JOIN not_defterleri ON notlar.not_defteri_id = not_defterleri.not_defteri_id");
                                    
                                    $stmt= $db->prepare("SELECT *  FROM notlar INNER JOIN not_defterleri ON not_defterleri.not_defteri_id = notlar.not_defteri_id WHERE not_defterleri.not_defteri_id=$not_defteri && notlar.tamamlandi=0" );
                                    $stmt->execute();
                                    if($stmt->rowCount()>0){
                                        echo "<p>". $stmt->rowCount() . " tane tamamlanmamış etkinlik bulunuyor. </p>";
                                    }
                                    else
                                        echo "<p>Tamamlanmamış etkinlik bulunmuyor.</p>";    
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                   
                <?php
                    }
                ?>
  <script>
     rand_color();
  </script>