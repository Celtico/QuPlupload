<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuPlupload\Model;

use Zend\Http\Headers;

class PluploadModel
{

    /**
     * @var
     */
    protected $id;

    /**
     * @var
     */
    protected $uploadDir;



    public function PluploadModel($data){


        if(!is_dir($this->uploadDir)){
           @mkdir($this->uploadDir);
           @chmod($this->uploadDir, 0777);
        }

        if (!is_writable($this->uploadDir)) {
            return false;
        }


        $contentType = '';
        $fileName_b = '';

        // HTTP headers for no cache etc
        $headers = new Headers();
        $headers->addHeaders(array(
           "Expires: Mon, 26 Jul 1997 05:00:00 GMT",
           "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT",
           "Cache-Control: no-store, no-cache, must-revalidate",
           "Cache-Control: post-check=0, pre-check=0", false,
           "Pragma: no-cache"
         ));


        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds

        // 5 minutes execution time
        @set_time_limit(5 * 60);

        // Uncomment this one to fake upload time
        // usleep(5000);

        // Get parameters
        $chunk = isset($data["chunk"]) ? intval($data["chunk"]) : 0;
        $chunks = isset($data["chunks"]) ? intval($data["chunks"]) : 0;
        $fileName = isset($data["name"]) ? $data["name"] : '';

        // Clean the fileName for security reasons
        $fileName = $this->id.'-'.preg_replace('/[^\w\._]+/', '-', $fileName);

        // Make sure the fileName is unique but only if chunking is disabled
        if ($chunks < 2 && file_exists($this->uploadDir . DIRECTORY_SEPARATOR . $fileName)) {
            $ext = strrpos($fileName, '.');
            $fileName_a = substr($fileName, 0, $ext);
            $count = 1;
            while (file_exists($this->uploadDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
                $count++;

            $fileName = $fileName_a . '_' . $count . $fileName_b;
        }

        $fileNameDb = $fileName;

        $filePath = $this->uploadDir . DIRECTORY_SEPARATOR . $fileName;

        // Create target dir
        if (!file_exists($this->uploadDir))
            @mkdir($this->uploadDir);

        // Remove old temp files
        if ($cleanupTargetDir) {
            if (is_dir($this->uploadDir) && ($dir = opendir($this->uploadDir))) {
                while (($file = readdir($dir)) !== false) {
                    $tmpFilePath = $this->uploadDir . DIRECTORY_SEPARATOR . $file;

                    // Remove temp file if it is older than the max age and is not the current file
                    if (preg_match('/\.part$/', $file) && (filemtime($tmpFilePath) < time() - $maxFileAge) && ($tmpFilePath != "{$filePath}.part")) {
                        @unlink($tmpFilePath);
                    }
                }
                closedir($dir);
            } else {

                throw new Exception\DomainException(
                    sprintf('{"jsonRpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory '.$this->uploadDir.' "}, "id" : "id"}')
                );
            }
        }

        // Look for the content type header
        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
            $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
            $contentType = $_SERVER["CONTENT_TYPE"];

        // Handle non multi part uploads older WebKit versions didn't support multipart in HTML5
        if (strpos($contentType, "multipart") !== false) {
            if (isset($data['file']['tmp_name']) && is_uploaded_file($data['file']['tmp_name'])) {
                // Open temp file
                $out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
                if ($out) {
                    // Read binary input stream and append it to temp file
                    $in = @fopen($data['file']['tmp_name'], "rb");

                    if ($in) {
                        while ($buff = fread($in, 4096))
                            fwrite($out, $buff);
                    } else

                        throw new Exception\DomainException(
                            sprintf('{"jsonRpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}')
                        );

                    @fclose($in);
                    @fclose($out);
                    @unlink($data['file']['tmp_name']);
                } else

                    throw new Exception\DomainException(
                        sprintf('{"jsonRpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}')
                    );

            } else

                throw new Exception\DomainException(
                    sprintf('{"jsonRpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}')
                );

        } else {
            // Open temp file
            $out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
            if ($out) {
                // Read binary input stream and append it to temp file
                $in = @fopen("php://input", "rb");

                if ($in) {
                    while ($buff = fread($in, 4096))
                        fwrite($out, $buff);
                } else

                    throw new Exception\DomainException(
                        sprintf('{"jsonRpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}')
                    );

                @fclose($in);
                @fclose($out);
            } else

                throw new Exception\DomainException(
                    sprintf('{"jsonRpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}')
                );
        }

        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
            // Strip the temp .part suffix off
            rename("{$filePath}.part", $filePath);
        }


        return array(
            'fileName'=>$fileNameDb,
            'filePath'=>$filePath
        );

    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        if(!$this->id){
            $this->setId(0);
        }
        return $this->id;
    }

    /**
     * @param $uploadDir
     * @return $this
     */
    public function setUploadDir($uploadDir)
    {
        $this->uploadDir = $uploadDir;
        return $this;
    }

    /**
     * @return string
     */
    public function getUploadDir()
    {
        return $this->uploadDir;
    }

}
