<?php

if (($_SERVER['REQUEST_METHOD'] === 'POST') &&
    (isset($_FILES['fileupload']))) {

    $allowed_types = array('image/jpeg', 'image/png', 'image/gif', 'video/mp4'); //danh sách loại file cho phép upload
    $files = $_FILES['fileupload'];

    $names      = $files['name'];
    $types      = $files['type'];
    $tmp_names  = $files['tmp_name'];
    $errors     = $files['error'];
    $sizes      = $files['size'];

    $numitems = count($names);
    $numfiles = 0;
    for ($i = 0; $i < $numitems; $i ++) {
        $filetype = mime_content_type($tmp_names[$i]); // kiểm tra định dạng file
        //Kiểm tra file thứ $i trong mảng file, up thành công không và có đúng định dạng không
        if ($errors[$i] == 0 && in_array($filetype, $allowed_types)) {
            $numfiles++;
            echo "Bạn upload file thứ $numfiles:<br>";
            echo "Tên file: $names[$i] <br>";
            echo "Lưu tại: 𝐘𝐨𝐮𝐫 𝐩𝐫𝐞-𝐝𝐢𝐫𝐞𝐜𝐭𝐨𝐫𝐲$names[$i] <br>";
            echo "Cỡ file: $sizes[$i] <br><hr>";

            move_uploaded_file($tmp_names[$i], 'uploads-cdn/'.$names[$i]);
        } else {
            echo "Upload không thành công: $names[$i] không phải là file ảnh hoặc video hợp lệ<br>";
        }
    }
    echo "Tổng số file upload: " .$numfiles;
}

?>

<form method="post" enctype="multipart/form-data">
    <p>Chọn file để upload:
      (Cỡ lớn nhất mà PHP đang cấu hình cho phép upload là <?=ini_get('upload_max_filesize')?>)</p>

    <input name="fileupload[]" type="file" multiple="multiple" />
    <input type="submit" value="Đăng ảnh" name="submit">
</form>
