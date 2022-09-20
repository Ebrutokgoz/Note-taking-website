<?php require_once 'header.php'; ?>

		<div class="row justify-content-center align-items-center h-100" >
			<div class="col col-sm-6 shadow rounded" style="background-image:url(images/color.png);">
				<b><div id="message" style="text-align:center; margin-top:10px;"></div></b>

				<form method="post" class="form-horizontal mx-5 my-5">
					<div class="form-group mb-2 ">
						<label class="col-sm-6 control-label">Kullanıcı adınız veya e-mail adresiniz :</label>
						<div class=""><input type="text" name="txt_username_email" class="form-control" value="<?php if(isset($_COOKIE["kullanici"])) { echo $_COOKIE["kullanici"]; } else{echo "";}?>"/>
						</div>
					</div>
					<div class="form-group mb-2">
						<label class="col-sm-6 control-label">Şifreniz :</label>
						<div class=""><input type="password" name="txt_password" class="form-control" value="<?php if(isset($_COOKIE["sifre"])) { echo $_COOKIE["sifre"]; } ?>"/></div>
					</div>
					<div class="form-group form-check mb-2">
						<div class="col-sm-6">
							<label class="form-check-label"><input class="form-check-input" type="checkbox" name="remember" checked> Beni hatırla</label>
						</div>
      				</div>
					<div class="form-group mb-3">
						<div class="col-sm-offset-3 col-sm-9 m-t-15"><input type="submit" name="btn_login" class="btn btn-secondary" value="Giriş"/></div>
					</div>
					<div class="form-group ">
						<div class="col-sm-offset-3 col-sm-9 m-t-15">
							Hâlâ bir hesabınız yok mu? <a href="register.php" style="text-decoration:none;"><p class="text-secondary">Hesap oluştur</p></a>
						</div>
					</div>
				</form>
			</div>
		</div>

	<?php
		require_once 'footer.php';
		require_once 'db_config.php';
		
		session_start();
		ob_start();
		// if(isset($_SESSION["user_login"])){
		// 	header("location:homepage.php");
		// } 
		if(isset($_REQUEST["btn_login"])){
			$username= strip_tags($_REQUEST["txt_username_email"]);
			$email = strip_tags($_REQUEST["txt_username_email"]);
			$password = strip_tags($_REQUEST["txt_password"]);

			if(empty($username)){
				$errorMsg[]="Kullanıcı adınızı veya e-postanızı giriniz!";
			}
			else if(empty($email)){
				$errorMsg[]="Kullanıcı adınızı veya e-postanızı giriniz!";
			}
			else if(empty($password)){
				$errorMsg[]="Şifrenizi giriniz!";
			}
			else{
				try{
					$select_stmt = $db->prepare("SELECT * FROM kullanicilar WHERE kullanici_ad=:k_ad OR kullanici_eposta=:k_eposta");
					$select_stmt->execute(array(':k_ad'=>$username, ':k_eposta'=>$email));
					$row=$select_stmt->fetch(PDO::FETCH_ASSOC);

					if($select_stmt->rowCount() > 0){
						if($username==$row["kullanici_ad"] OR $email==$row["kullanici_eposta"]){
							// if(password_verify($password, $row["kullanıcı_sifre"])){
							if($password == $row["kullanici_sifre"]){
								if(!empty($_POST["remember"])) {
									setcookie ("kullanici",$_POST["txt_username_email"],time()+ (5 * 365 * 24 * 60 * 60));
									setcookie ("sifre",$_POST["txt_password"],time()+ (5 * 365 * 24 * 60 * 60));
								} else {
									setcookie("kullanici",$_POST["txt_username_email"],time()- 3600);
									setcookie("sifre",$_POST["txt_password"],time()- 3600);
								}
								$_SESSION["user_login"]= $row["kullanici_id"];
								$loginMsg = "Giriş yapılıyor...";
								header("refresh:1; index.php");
							}
							else{
								$errorMsg[]="Şifre hatalı!";
							}
						}
						else{
							$errorMsg[]="Kullanıcı adı veya e-posta hatalı!";
						}
					}
					else{
						$errorMsg[]="Kullanıcı adı veya e-posta hatalı!";
					}
				}
				catch(PDOException $e){
					$e->getMessage();
				}
			}
		} 
		if(isset($errorMsg)){
			foreach($errorMsg as $error){
			echo "<script>
			document.getElementById('message').innerHTML='$error';
					</script>";	
			}
		}
		if(isset($loginMsg)){
			echo "<script>
			document.getElementById('message').innerHTML='$loginMsg';
		</script>";	
		}
	?>
	




