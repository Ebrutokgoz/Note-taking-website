<?php 
	require_once "db_config.php";
	require_once 'header.php';
	session_start();
	ob_start();
	
		
		if(isset($_REQUEST["btn_register"])){
			$username= strip_tags($_REQUEST["txt_username"]);
			$email = strip_tags($_REQUEST["txt_email"]);
			$password = strip_tags($_REQUEST["txt_password"]);

			if(empty($username)){
				$errorMsg[]="Kullanıcı adı belirleyiniz.";
			}
			else if(empty($email)){
				$errorMsg[]="E-posta adresinizi giriniz.";
			}
			else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$errorMsg[]="Geçerli bir e-posta adresi giriniz!";
			}
			else if(empty($password)){
				$errorMsg[] = "Şifre belirleyiniz.";
			}
			else if(strlen($password) < 6){
				$errorMsg[] = "Şifreniz en az 6 karakter içermeli!";
			}
			else{
				try{
					$select_stmt=$db->prepare("SELECT kullanici_ad, kullanici_eposta FROM kullanicilar
												WHERE kullanici_ad=:k_ad OR kullanici_eposta=:k_eposta");
					$select_stmt->execute(array(':k_ad'=>$username, ':k_eposta'=>$email));
					$row=$select_stmt->fetch(PDO::FETCH_ASSOC);

					if($row['kullanici_ad']==$username){
						$errorMsg[]="Üzgünüz, bu ad başka bir kullanıcı tarafından alınmış.";
					}
					else if($row['kullanici_eposta']==$email){
						$errorMsg[]="Bu e-posta adresine ait bir hesap zaten var.";
					}
					else if(!isset($errorMsg)){
						//$new_password = password_hash($password, PASSWORD_DEFAULT);
						$insert_stmt=$db->prepare("INSERT INTO kullanicilar (kullanici_ad,kullanici_eposta,kullanici_sifre)
													VALUES (:k_ad, :k_eposta, :k_sifre)");
						if($insert_stmt->execute(array(':k_ad'=>$username,':k_eposta'=>$email,':k_sifre'=>$password))){
							$registerMsg[]= "Artık üyesiniz! Hesabınıza giriş yapabilirsiniz.";
						}
						header("refresh:1; login.php");
					}
				}catch(PDOException $e){
					echo $e->getMessage();
				}
			}
		}

	?>

	<div class="row justify-content-center align-items-center h-100" >
		<div class="col col-sm-6 shadow rounded" style="background-image:url(images/color.png);">
			<b><div id="message" style="text-align:center; margin-top:10px;"></div></b>

			<form method="post"  class="form-horizontal mx-5 my-5">
				<div class="form-group mb-2">
					<label class="col-sm-3 control-label">Kullanıcı adı</label>
					<div class="">
						<input type="text" name="txt_username" class="form-control" />
					</div>
				</div>
				<div class="form-group mb-2">
					<label class="col-sm-3 control-label">E-posta</label>
					<div class="">
						<input type="text" name="txt_email" class="form-control" />
					</div>
				</div>
				<div class="form-group mb-2">
					<label class="col-sm-3 control-label">Şifre</label>
					<div class="">
						<input type="password" name="txt_password" class="form-control" />
					</div>
				</div>
				<div class="form-group mb-2">
					<div class="col-sm-offset-3 col-sm-9 m-t-15">
						<input type="submit" name="btn_register" class="btn btn-secondary" value="Üye ol" />
					</div>
				</div>
				<div class="form-group mb-2">
					<div class="col-sm-offset-3 col-sm-9 m-t-15">
						Zaten bir hesabınız var mı? <a href="login.php"><p class="text-secondary">Giriş</p></a>
					</div>
				</div>
				
			</form>
		</div>
	</div>
	
<?php
		require_once 'footer.php';
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