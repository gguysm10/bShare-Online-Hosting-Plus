<?php

// FILE upload.php PROCESSING FILES

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Check if the request method is POST.
    echo "The request method is not POST.";
    die;
}

// Check if the file upload field is empty.
// If data empty then stop
if (!isset($_FILES["fileupload"])) {
    echo "The file upload field is empty.";
    die;
}

// Check if there was an error uploading the file.
if ($_FILES["fileupload"]['error'] != 0) {
    echo "An error occurred. There was an error uploading the file, error code: $error_code.";
    die;
}

// First check successfully, then continue these steps

// Folder
$target_dir = "uploads-cdn/";
// Temporary file (data will be saved in uploads with its own name)
$extension = pathinfo($_FILES["fileupload"]["name"], PATHINFO_EXTENSION);
$randomName = generateRandomName();
$target_file = $target_dir . $randomName . '.' . $extension;

$allowUpload = true;

// Get the file upload extension information.
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

// Max upload file size
$maxfilesize = 8900000;

//// Check if the file type is allowed.
$allowtypes = array('jpg', 'png', 'jpeg', 'gif');

if (isset($_POST["submit"])) {
    // Check if the file type is allowed. Is it an image?
    $check = getimagesize($_FILES["fileupload"]["tmp_name"]);
    if ($check !== false) {
        echo "Image file(s) - " . $check["mime"] . ".";
        $allowUpload = true;
    } else {
        echo "Not an image file(s).";
        $allowUpload = false;
    }
}

// Check if the file already exists and do not allow overwriting.
// You can develop the code to save it as a different filename
if (file_exists($target_file)) {
    echo "The file name already exists on the server, please rename your file and try again.";
    $allowUpload = false;
}

// Check if the upload file size exceeds the allowed limit
if ($_FILES["fileupload"]["size"] > $maxfilesize) {
    echo "You cannot upload images larger than $maxfilesize (bytes).";
    $allowUpload = false;
}

// Check file type
if (!in_array($imageFileType, $allowtypes)) {
    echo "Only JPG, PNG, JPEG, GIF formats are allowed.";
    $allowUpload = false;
}

if ($allowUpload) {
    // Move the temporary file to the storage directory using the move_uploaded_file function
    if (move_uploaded_file($_FILES["fileupload"]["tmp_name"], $target_file)) {
        echo "File " . basename($_FILES["fileupload"]["name"]) .
            " has been successfully uploaded.";

        echo "File saved in your directory: 𝐘𝐨𝐮𝐫 𝐩𝐫𝐞-𝐝𝐢𝐫𝐞𝐜𝐭𝐨𝐫𝐲" . $target_file;
    } else {
        echo "There was an error uploading the file.";
    }
} else {
    echo "File upload failed, it may be due to a large file, incorrect file type, etc.";
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
