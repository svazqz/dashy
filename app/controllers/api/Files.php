<?php

namespace Controllers\API;

use Core;

class Files extends Core\APIController {
  public function show() {
    $target_dir = getcwd() . "/html/assets/";
    $images = [];
    if (is_dir($target_dir)) {
        foreach (glob($target_dir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE) as $file) {
            $images[] = basename($file);
        }
    }
    echo json_encode($images);
  }

  public function create() {
    $target_dir = getcwd() . "/html/assets/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $response = ['success' => false, 'message' => ''];
    
    if (isset($_FILES['image'])) {
        $file = $_FILES['image'];
        $fileName = basename($file['name']);
        $targetPath = $target_dir . $fileName;
        
        $imageFileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $response = ['success' => true, 'message' => 'File uploaded successfully'];
            } else {
                $response['message'] = 'Error uploading file';
            }
        } else {
            $response['message'] = 'Only JPG, JPEG, PNG & GIF files are allowed';
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
  }
  
  public function delete($filename = "") {
    $filepath = getcwd() . "/html/assets/" . $filename;
    if (file_exists($filepath) && unlink($filepath)) {
        echo json_encode(['success' => true, 'message' => 'File deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting file']);
    }
  }
}