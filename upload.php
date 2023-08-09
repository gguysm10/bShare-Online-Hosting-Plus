<?php
  
  // FILE upload.php PROCESSING FILES

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') 
  {
      // Check if the request method is POST.
      echo "The request method is not POST.";
      die;
  }

  // Check if the file upload field is empty.
  // If data empty then stop
  if (!isset($_FILES["fileupload"])) 
  {
      echo "The file upload field is empty.";
      die;
  }

  // Check if there was an error uploading the file.
  if ($_FILES["fileupload"]['error'] != 0)
  {
    echo "An error occured. There was an error uploading the file, error code: $error_code.";
    die;
  }

  // First check successfully, then continue these steps

  // Folder
  $target_dir    = "uploads-cdn/";
  // Temporary file (data will save in uploads with it's own name)
  $target_file   = $target_dir . basename($_FILES["fileupload"]["name"]);

  $allowUpload   = true;

  // Get the file upload extension information.
  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

  // Max upload file size
  $maxfilesize   = 8900000; 

  //// Check if the file type is allowed.
  $allowtypes    = array('jpg', 'png', 'jpeg', 'gif');


  if(isset($_POST["submit"])) {
      // Check if the file type is allowed. It's image?
      $check = getimagesize($_FILES["fileupload"]["tmp_name"]);
      if($check !== false) 
      {
          echo "Image file(s) - " . $check["mime"] . ".";
          $allowUpload = true;
      } 
      else 
      {
          echo "Not image file(s).";
          $allowUpload = false;
      }
  }

  // Check if file already exists then do not allow overwriting.
  // You can develop the code to save as a different filename
  if (file_exists($target_file)) 
  {
      echo "Tên file đã tồn tại trên server, không được ghi đè";
      $allowUpload = false;
  }
  // Kiểm tra kích thước file upload cho vượt quá giới hạn cho phép
  if ($_FILES["fileupload"]["size"] > $maxfilesize)
  {
      echo "Không được upload ảnh lớn hơn $maxfilesize (bytes).";
      $allowUpload = false;
  }


  // Kiểm tra kiểu file
  if (!in_array($imageFileType,$allowtypes ))
  {
      echo "Chỉ được upload các định dạng JPG, PNG, JPEG, GIF";
      $allowUpload = false;
  }

  
  if ($allowUpload) 
  {
      // Xử lý di chuyển file tạm ra thư mục cần lưu trữ, dùng hàm move_uploaded_file
      if (move_uploaded_file($_FILES["fileupload"]["tmp_name"], $target_file))
      {
          echo "File ". basename( $_FILES["fileupload"]["name"]).
          " Đã upload thành công.";

          echo "File lưu tại: 𝐘𝐨𝐮𝐫 𝐩𝐫𝐞-𝐝𝐢𝐫𝐞𝐜𝐭𝐨𝐫𝐲" . $target_file;

      }
      else
      {
          echo "Có lỗi xảy ra khi upload file.";
      }
  }
  else
  {
      echo "Không upload được file, có thể do file lớn, kiểu file không đúng ...";
  }
?>
