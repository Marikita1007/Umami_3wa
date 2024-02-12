<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function Symfony\Component\Translation\t;

class SimpleUploadService
{
    //Access to service.yaml
    //Destination path defined in the file service.yaml parameters = images_directory;
    //images_directory: '%kernel.project_dir%/public/images/'
    public function __construct(private ParameterBagInterface $param){}

    /**
     * Uploads an image file to the specified directory.
     *
     * @param UploadedFile $file The uploaded file to be processed.
     *
     * @return string The newly generated file name after upload.
     */
    public function uploadImage(UploadedFile $file){

        // Extract the original file name without the extension
        $original_file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Generate a unique file name by appending a unique identifier and the original file extension
        $new_file_name = $original_file_name.'-'.uniqid().'.'.$file->guessExtension();

        // Get the destination path from the service parameters
        $path_destination = $this->param->get('recipe_image_directory');

        // Move the uploaded file to the specified destination path with the new file name
        $file->move( // Same as php moveUploadFile()
            $path_destination,
            $new_file_name
        );

        // Return the newly generated file name after upload
        return $new_file_name;
    }

    /**
     * Deletes an image file from the specified directory.
     *
     * @param string $file The file name to be deleted.
     *
     * @return bool True if the file is successfully deleted, false otherwise.
     */
    public function deleteImage(string $file)
    {
        // Get the path to the image directory from the service parameters
        $path = $this->param->get('recipe_image_directory');

        // Construct the full path to the image file
        $image_object = $path . "/" . $file;

        // Initialize success flag
        $success = false;

        // Check if the file exists before attempting to delete
        if (file_exists($image_object))
        {
            // Delete the file
            unlink($image_object);
            $success = true;

            return $success;
        }

        // Return false if the file does not exist
        return false;
    }

}