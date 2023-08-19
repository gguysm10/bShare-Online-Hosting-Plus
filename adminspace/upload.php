<?php

// FILE upload.php PROCESSING FILES

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Check if the request method is POST.
    echo "Yêu cầu dữ liệu không hợp lệ, phải là POST.";
    die;
}

// Check if the file upload field is empty.
// If data empty then stop
if (!isset($_FILES["fileupload"])) {
    echo "Dữ liệu trống.";
    die;
}

// Check if there was an error uploading the file.
if ($_FILES["fileupload"]['error'] != 0) {
    echo "Lỗi tải file, mã lỗi: $error_code.";
    die;
}

// First check successfully, then continue these steps

// Folder
$target_dir = "../tailieu/";
// Temporary file (data will be saved in uploads with its own name)
$extension = pathinfo($_FILES["fileupload"]["name"], PATHINFO_EXTENSION);
$randomName = generateRandomName();
$target_file = $target_dir . basename( $_FILES["fileupload"]["name"]) . ㅤㅤFileID . $randomName . '.' . $extension;

$allowUpload = true;

// Get the file upload extension information.
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

// Max upload file size
$maxfilesize = 8900000;

//// Check if the file type is allowed.
$allowtypes = array('jpg', 'png', 'jpeg', 'gif', 'doc', 'docx', 'docm', 'pdf', 'xlsx', 'xls', 'html', 'htm', 'wps', 'rtf', 'txt', 'pptx', 'ppt', 'odt', 'ods', 'odp');

if (isset($_POST["submit"])) {
    // Check if the file type is allowed. Is it an image?
    $check = getimagesize($_FILES["fileupload"]["tmp_name"]);
    if ($check !== false) {
        echo "Tệp chính xác - " . $check["mime"] . ".";
        $allowUpload = true;
    } else {
        echo "Chưa được hỗ trợ dạng tệp này. ";
        $allowUpload = true;
    }
}

// Check if the file already exists and do not allow overwriting.
// You can develop the code to save it as a different filename
if (file_exists($target_file)) {
    echo "Tệp đã tồn tại.";
    $allowUpload = false;
}

// Check if the upload file size exceeds the allowed limit
if ($_FILES["fileupload"]["size"] > $maxfilesize) {
    echo "File quá lớn. Dung lượng tối đa cho phép là $maxfilesize (bytes).";
    $allowUpload = false;
}

// Check file type
if (!in_array($imageFileType, $allowtypes)) {
    echo "Tệp tin không được hỗ trợ.";
    $allowUpload = false;
}

if ($allowUpload) {
    // Move the temporary file to the storage directory using the move_uploaded_file function
    if (move_uploaded_file($_FILES["fileupload"]["tmp_name"], $target_file)) {
        echo "Tệp " . basename($_FILES["fileupload"]["name"]) .
            " Tải lên thành công!.";

        echo "Thư mục: " . $target_file;
    } else {
        echo "Xảy ra lỗi, nhấn F5 hoặc tải lại trang để thử lại.";
    }
} else {
    echo "Tải lên tệp không thành công, có thể do tệp lớn, loại tệp không chính xác, v.v.";
}

// Function to generate a random name for the file
function generateRandomName()
{
    $characters = '0123456789';
    $randomName = '';

    for ($i = 0; $i < 20; $i++) {
        $randomName .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomName;
}
?>
