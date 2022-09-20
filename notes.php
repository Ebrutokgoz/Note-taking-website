    <?php
        require_once 'header_mainpage.php';
        require_once "db_config.php";
        session_start();
        ob_start();

        $sql=$db->prepare("SELECT * FROM not_defterleri WHERE not_defteri_id=?");
        $sql->execute([htmlspecialchars($_GET['not_defteri_id'])]);
        $row=$sql->fetch(PDO::FETCH_ASSOC);

        if(isset($_REQUEST["btn_add"])){
            $not= strip_tags($_REQUEST["txt_not"]);
            $not_defteri=$row['not_defteri_id'];

            if(empty($not_defteri)){
				$errorMsg[]="Not giriniz.";
			}
            else{
                try{
                    $insert_stmt=$db->prepare("INSERT INTO notlar (not_aciklama,not_defteri_id,tamamlandi)
                                                VALUES (:n_aciklama,:nd_id,:tamamlandi)");
                    if($insert_stmt->execute(array(':n_aciklama'=>$not,':nd_id'=>$not_defteri,':tamamlandi'=>0))){
                        $registerMsg[]= "Not oluşturuldu.";
                        }
                    header("refresh:2; detay.php");
                }catch(PDOException $e){
                    echo $e->getMessage();
                }
            }
        }
        if(isset($errorMsg)){
            foreach($errorMsg as $error){
                ?>
				    <div class="alert alert-danger">
					    <strong>Bir hata oluştu! <?php echo $error; ?></strong>
				    </div>
				<?php
            }
        }

    ?>
        <div class="row">
            <div class="col">
                <div class="card text-white shadow">
                    <div class="card-img-overlay d-flex align-items-center justify-content-center">
                        <h1 id="ad"><?php echo $row['not_defteri_ad'] ?></h1>
                    </div>
                    <img src="images/color.png">
                </div>

            </div>
            <div class="col">

                <div class="card px-3 shadow">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Notlar</h4>
                        <div class="add-items d-flex mb-3"> <input type="text" name="txt_not" class="form-control todo-list-input"> 
                         <button class="mx-2 add btn btn-primary font-weight-bold todo-list-add-btn" name="btn_add">Ekle</button> </div>
                        <!-- <input type='submit' name='btn_add' class='btn btn-primary' value='Ekle' /> </div> -->
                        <div class="list-wrapper">
                            <ul class="d-flex flex-column-reverse todo-list list-unstyled">
                                <?php
                                    $sql=$db->prepare("SELECT * FROM notlar WHERE not_defteri_id=? Order By tarih DESC");
                                    $sql->execute([htmlspecialchars($row['not_defteri_id'])]);
                                    while($row=$sql->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                <li>
                                    <div class="form-check"> 
                                        <button style="border:0; text-justify:right;"><i class="fas fa-trash"></i></button>
                                            <input class="checkbox mx-1" type="checkbox"> 
                                            <label class="form-check-label"> <?php echo $row['not_aciklama'];?></label>
                                    </div>
                                </li>
                                <?php
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>  
    </div>
        
<?php require_once 'footer.php'; ?>