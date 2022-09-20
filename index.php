<?php 
	require_once "db_config.php";
	require_once 'header_mainpage.php';
	session_start();
	ob_start();
	
		
		if(isset($_REQUEST["btn_add"])){
			$not_defteri= strip_tags($_REQUEST["txt_not_defteri"]);
			$kullanici_id=$_SESSION["user_login"];

			if(empty($not_defteri)){
				$errorMsg[]="Not defteriniz için isim giriniz.";
			}
            // Not defteri zaten veritabanında varsa
			// else if(empty($email)){
			// 	$errorMsg[]="please enter email";
            // }
			else{
				try{
                    //$insert_stmt=$db->prepare("INSERT INTO not_defterleri (not_defteri_ad,kullanıcı_id)
                    //VALUES (?, ?)");
                    // if($insert_stmt->execute([$not_defteri,$kullanici_id])){
                    // if($insert_stmt->execute(array($not_defteri,$kullanici_id))){
                    $insert_stmt=$db->prepare("INSERT INTO not_defterleri (not_defteri_ad,kullanici_id)
												VALUES (:nd_ad, :k_id)");
					if($insert_stmt->execute(array(':nd_ad'=>$not_defteri,':k_id'=>$kullanici_id))){
						$registerMsg[]= "Not defteri oluşturuldu.";
                    }
                    header("refresh:1; index.php");
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
        <div class="col-md-8 col-lg-9">
            <div class="row mb-3" >
                
                <!-- <div class="col-md-6 col-lg-5" id='add-body'>
                    <div class="card not_defteri" style="background:linear-gradient(45deg, #D8BFD8,rgba(0,0,0,.03));">
                        <div class='p-3'>
                            <form method='post' class='form-horizontal'>
                                <div class='form-group'>
                                    <label class='col-sm-3 control-label'>not_defteri</label>
                                     <div class='col-sm-6'>
                                    <input type='text' name='txt_not_defteri' class='form-control' placeholder='Enter' />
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <div class='col-sm-3 col-sm-9 m-t-15'>
                                        <input type='submit' name='btn_add' class='btn btn-primary' value='Ekle' />
                                    </div>
                                </div>
                         </form>
                        </div>
                    </div>
                                        
                </div>  -->
                <div class=" add">
                    <div class="card defter rounded shadow" style="border:3px solid rgba(0,0,0,.03);">
                        <!-- <div class="card">
                            <img src="images/plus.png" class="card-img-top">
                        </div> -->
                        <div class='p-3'>
                            <form method='post' class='form-inline'>
                                <div class='form-group'>
                                    <label class='col-sm-3 control-label mb-3 mx-2'>Yeni bir not defteri ekleyin</label>
                                     <div class='col-sm-6 mb-3'>
                                    <input type='text' name='txt_not_defteri' class='form-control' />
                                    </div>
                                </div>
                                
                                <div class='form-group'>
                                    <div class='col-sm-3 col-sm-9 m-t-15 mb-3'>
                                        <input type='submit' name='btn_add' class='btn btn-primary' value='Ekle' />
                                    </div>
                                </div>
                         </form>
                        </div>
                        <!-- <div class="card-body shadow">
                            <h5 class="card-title"> Ekle</h5>
                            <p>
                                Yeni bir not defteri ekleyin
                            </p>
                        </div> -->
                    </div>
                    
                </div>
            </div>
            <div class="row mb-3" id="new-data">
                <?php
                    $sql=$db->prepare("SELECT * FROM not_defterleri WHERE kullanici_id=:k_id Order By not_defteri_id DESC Limit 6");
                    $sql->execute(array(':k_id'=>$_SESSION["user_login"]));
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
            </div> 
             
        </div>  

            

        <div class="col-md-4 col-lg-3 order-first order-md-last mb-5">
            <div class="list-group shadow sticky-top">
                <a href="#" class="list-group-item list-group-item-action text-white list-group-item-dark" class="card-img-top">Not Defterlerim</a>
                <?php
                    $sql=$db->prepare("SELECT * FROM not_defterleri WHERE kullanici_id=:k_id Order By not_defteri_ad DESC Limit 20");
                    $sql->execute(array(':k_id'=>$_SESSION["user_login"]));
                    while($row=$sql->fetch(PDO::FETCH_ASSOC)){
                ?>
                <a href="notes.php?not_defteri_id=<?php echo $row['not_defteri_id'] ?>" class="list-group-item list-group-item-action"><?php echo $row['not_defteri_ad'] ?></a>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>

    <?php require_once 'footer.php';?>

    <script type="text/javascript">
        console.log($(window).scrollTop());
        console.log($(window).height());
        console.log($(document).height());

        $(window).scroll(function(){
            if($(window).scrollTop()+$(window).height()>=$(document).height()){
                var last_id=$(".not_defteri_id:last").attr("id");
                newData(last_id);
            }
        });
    

        function newData(last_id){
            $.ajax({
                url:'newData.php?last_id='+last_id,
                type:'GET',
                success:function(msg){
                    $("#new-data").append(msg);
                },
                error:function(msg){
                    alert("Veri çekilemiyor");
                }
             });
        }
    </script>
        