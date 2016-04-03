<?php
/**
 * Description of UploadImage.php.
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 * @package Bodeven
 */

namespace __MODULE__;


class UploadImage {

    /**
     * @var array
     */
    var $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

    /**
     * @var bool
     */
    var $ignore_uploads = false;

    /**
     * @var string
     */
    var $upload_dir = '';

    /**
     * @param $status
     * @param $vars
     * @param $type
     * @return JSON|string
     */
    protected function exitStatus($status, $vars = array(), $type = 'json') {
        if (strtolower($type) == 'json') {
            return json_encode(
                array(
                    'status' => $status,
                    'vars' => $vars
                )
            );
        } else {
            return array(
                'status' => $status,
                'vars' => $vars
            );
        }
    }

    /**
     * @param $file_name
     * @return string
     */
    protected function getExtension($file_name) {
        $ext = explode('.', $file_name);
        $ext = array_pop($ext);
        return strtolower($ext);
    }

    /**
     * @param $file
     * @return JSON
     */
    public function uploadFile($file) {

        if ($file['error'] == 0) {

            if (!in_array( $this->getExtension($file['name']), $this->allowed_ext)) {
                return $this->exitStatus('Only '.implode(',',$this->allowed_ext).' files are allowed!');
            }

            if ($this->ignore_uploads) {
                // File uploads are ignored. We only log them.

                $line = implode('		', array( date('r'), $_SERVER['REMOTE_ADDR'], $file['size'], $file['name']));
                file_put_contents('log.txt', $line.PHP_EOL, FILE_APPEND);

                return $this->exitStatus('Uploads are ignored in demo mode.');
            }

            // Create the upload dir if don't exists
            if (!is_null($this->upload_dir) && !file_exists($this->upload_dir)) {
                @mkdir($this->upload_dir);
            }

            // Date
            $date = date("d-m-Y");
            if (!file_exists($this->upload_dir . "$date")) {
                @mkdir($this->upload_dir . "$date");
            }

            // Move the uploaded file from the temporary
            // directory to the uploads folder:

            if(move_uploaded_file($file['tmp_name'], $this->upload_dir . "$date/" . $file['name'])) {
                $message = array(
                    'folder' => $date,
                    'imagen' => $file['name']
                );
                return $this->exitStatus('File was uploaded successfuly!', $message, 'array');
            }
        } else {
            return $this->exitStatus('Something went wrong with your upload!');
        }
    }
} 