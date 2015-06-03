<?php 
	//panggil koneksi
	include "koneksi.php";
	//cek apakah ada action atau tidak
	if(isset($_GET['act']) AND $_GET['act'])
	{
		if($_GET['act'] == "add")
		{
			//create form add
			include "add-biodata.html";
		}
		elseif($_GET['act'] == "create")
		{
			//echo "action create";
			//cek apakah form dapat mengirimkan nilai pada biodata.php
			//print_r($_POST);
			//cek apakah nilai dikirimkan dari form atau tidak
			if(isset($_POST['f_submit']) AND $_POST['f_submit']) //jika ya
			{
				//masukan nilai input pada form pada variable agar lebih ringkas
				$nama = $_POST['f_nama'];
				$email = $_POST['f_email'];
				$telp = $_POST['f_telp'];
				$alamat = $_POST['f_alamat'];
				$jk = (isset($_POST['f_jk'])?$_POST['f_jk']:null);
				$agama = $_POST['f_agama'];
				$hobi = (isset($_POST['f_hobi'])?$_POST['f_hobi']:array());
				
				//cara simple
				$hobi = implode(",", $hobi);
				
				//----------------------------------------------------
				//cara dengan melakukan perulangan
				//karena hobi adalah array jadi harus melakukan perulangan untuk mendapatkan nilai menjadi satu inputan
				/*$hobinya = "";
				if(count($hobi) > 0)
				{
					foreach($hobi as $v_hobi)
					{
						$hobinya .= $v_hobi .","; //menggabungkan nilai hobi menjadi satu baris kemudian dipisahkan anda `,`
					}
					$hobinya = substr($hobinya, 0, -1); //menghilangkan tanda `,` pada akhir string karena nilai terakhir berupa kosong
					//echo $hobinya;
				}*/
				//-------------------------------------------------------
				
				//upload gambar
				//cek apakah gambar diinputkan atau tidak
				//jenis upload pengambilan nilai tidak sama dengan input di atas menggunakan $_POST tapi dengan variable $_FILES
				if($_FILES['f_foto']['size'] > 0) //cek size gambar untuk mengetahui eventnya
				{
					//Array ( [f_foto] => Array ( [name] => logo.png [type] => image/png [tmp_name] => D:\SOURCE\xampp\tmp\php3574.tmp [error] => 0 [size] => 1546 ) )
					//semua hasil print dari gambar dapat dimanfaatkan untuk mengapload file ke folder yang kita inginkan
					
					//print_r($_FILES);
					
					$foto = $_FILES['f_foto']['name'];
					//copy(temporary folder, folder tujuan beserta nama filenya);
					//mengambil variable yang ada pada variable server `$_SERVER`
					//print_r($_SERVER);
					copy($_FILES['f_foto']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] ."/webku/upload/". $foto); //perintah untuk menduplikat gambar yang ada pada temporary ke folder yang kita inginkan
				}
				else //jika tidak ada
				{
					//echo "tidak ada gambar yang diinput.";
					$foto = null;
				}
				//cek apakah nilai inputan telah dapat diambil atau tidak
				/*
				echo $nama ."<br/>";
				echo $email ."<br/>";
				echo $telp ."<br/>";
				echo $alamat ."<br/>";
				echo $hobinya ."<br/>";
				echo $hobi ."<br/>";
				echo $agama ."<br/>";
				echo $jk ."<br/>";
				echo $foto ."<br/>";
				*/
				//semua nilai inputan telah dapat diambil
				//validasi form agar kosong tidak dapat disimpan
				if($nama AND $email AND $telp AND $alamat AND $hobi AND $agama AND $jk AND $foto)
				{
					//echo "OK";
					//buat perintah query untuk menyimpan hasil inputan dari form
					$QUERY = "INSERT INTO biodata (nama, alamat, no_telp, email, jenis_kelamin, hobi, agama, foto) VALUES ('$nama', '$alamat', '$telp', '$email', '$jk', '$hobi', '$agama', '$foto')";
					//cek apakah perintah query telah berjalan dengan benar atau belum
					//echo $QUERY;
					//jika pada phpmyadmin telah berhasil, jalan kan untuk eksekusi ke database saat form disubmit
					//perintah eksekusi query pada mysql
					mysql_query($QUERY);
					//panggil koneksi ke database agar perintah dapat dijalankan
					header("location:biodata.php"); //arahkan halaman ke halaman view list - pada belajar selanjutnya ..
				}
				else
				{
					echo "Form harus diisi!";
				}
			}
			else //jika tidak
			{
				header("location:biodata.php?act=add"); //arahkan halaman ke halaman add
			}
		}
	}
	else
	{
		//view list biodata
		//buat perintah query untuk menampilkan data pada table `biodata`
		$QUERY = "SELECT * FROM biodata";
		$RESULT = mysql_query($QUERY);
		
		include "list-biodata.html";
	}
?>