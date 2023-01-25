<?php

class FileUpload {
  
  private $uploadDirectory;
  private $allowedExtensions;
  private $maxFileSize;
  private $permissions;

  public function __construct($uploadDirectory, $allowedExtensions = array('jpg', 'jpeg', 'png', 'pdf'), $maxFileSize = 1000000, $permissions = 0644) {
    $this->uploadDirectory = $uploadDirectory;
    $this->allowedExtensions = $allowedExtensions;
    $this->maxFileSize = $maxFileSize;
    $this->permissions = $permissions;
    if (!is_dir($this->uploadDirectory)) {
      mkdir($this->uploadDirectory, $this->permissions, true);
    }
  }

  public function upload(array $file) {
    if (empty($file)) {
      throw new Exception("No file provided for upload.");
    }

    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    if (!in_array($fileActualExt, $this->allowedExtensions)) {
      throw new Exception("Invalid file type. Only " . implode(',', $this->allowedExtensions) . " files are allowed.");
    }

    if ($fileError !== UPLOAD_ERR_OK) {
      switch ($fileError) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
          $message = "File size exceeds the maximum limit of " . $this->maxFileSize . " bytes.";
          break;
        case UPLOAD_ERR_PARTIAL:
          $message = "The uploaded file was only partially uploaded.";
          break;
        case UPLOAD_ERR_NO_FILE:
          $message = "No file was uploaded.";
          break;
        case UPLOAD_ERR_NO_TMP_DIR:
          $message = "Missing a temporary folder.";
          break;
        case UPLOAD_ERR_CANT_WRITE:
          $message = "Failed to write file to disk.";
          break;
        case UPLOAD_ERR_EXTENSION:
          $message = "A PHP extension stopped the file upload.";
          break;
        default:
          $message = "Unknown error occurred.";
          break;
      }
      throw new Exception("An error occurred while uploading the file: " . $message);
    }

    if ($fileSize > $this->maxFileSize) {
      throw new Exception("File size exceeds the maximum limit of " . $this->maxFileSize . " bytes.");
    }

    $fileNameNew = uniqid('', true).".".$fileActualExt;
    $fileDestination = $this->uploadDirectory.'/'.$fileNameNew;
    
    if (!move_uploaded_file($fileTmpName, $fileDestination)) {
      throw new Exception("Failed to move the uploaded file.");
    }
    chmod($fileDestination, $this->permissions);

    // Additional processing can be done here, such as image resizing,
    // file compression, or virus scanning.

    return $fileNameNew;
  }
  
}
