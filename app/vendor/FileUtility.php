<?php
class FileUtility
{
    const use_s3 = ENV_S3;
    public $maxSize, $ext, $path, $errors;
    public $filename, $extension, $file;
    // static variables
    public static $FIRST = 1, $LAST = 2;
    /**
     * Constructor
     * 
     * @param int $maxSize
     * @param array $extensions
     * @param array $options
     */
    public function __construct($maxSize, $extensions = array())
    {
        $this->maxSize = $maxSize;
        $this->ext = $extensions;

        foreach ($this->ext as $k => $ext)
        {
            $this->ext[$k] = strtolower(trim($ext));
        }
    }

    /**
     * 
     * upload a file to destination
     * @param array $file
     * @param string $dest_path
     * @return boolean
     */
    public function uploadFile($file, $dest_path, $filename = "")
    {
        //validating file
        $this->errors = array();
        if (!$this->validateFile($file))
        {
            return false;
        }

        //creating folder
        $dest_path = Util::removePathSlashs($dest_path);
        $dest_path .= "/";

        self::createFolder($dest_path);

        $this->path = $dest_path;

        $temp = pathinfo($file["name"]);

        $this->filename = strClean($temp['filename']);
        $this->extension = $temp['extension'];

        if ($filename)
        {
            $this->filename = strClean($filename);
            if (strlen($this->filename) > 100)
            {
                $this->filename = substr($this->filename, 0, 100);
            }

            $this->file = $this->filename . "." . $this->extension;
        }
        else
        {
            if (strlen($this->filename) > 100)
            {
                $this->filename = substr($this->filename, 0, 100);
            }

            $this->file = self::getAutoincreamentFileName($this->filename, $this->extension, $dest_path);
            $this->filename = pathinfo($this->file, PATHINFO_FILENAME);
        }

        return move_uploaded_file($file['tmp_name'], $this->path . $this->file);
    }

    /**
     * validate the file
     * @param string $file
     * @return boolean
     */
    public function validateFile($file)
    {
        $result = true;

        if ($file['size'] > $this->maxSize)
        {
            $this->errors[] = "File size must not exceeds " . round($this->maxSize / 1024) . " kb";
            $result = false;
        }

        $temp = pathinfo($file["name"]);

        $this->filename = $temp['filename'];
        if (!isset($temp['extension']) || !$temp['extension'])
        {
            $this->errors[] = "Could not find type of file";
            $result = false;
        }
        else
        {
            $this->extension = strtolower($temp['extension']);

            if (!empty($this->ext) && !in_array($this->extension, $this->ext))
            {
                $this->errors[] = "Invalid file Type : " . $this->extension;
                $result = false;
            }
        }

        return $result;
    }

    /**
     * return filename which which will be save 
     * @param string $filename
     * @param string $ext
     * @param string $dest_path
     * @return string
     */
    public static function getAutoincreamentFileName($filename, $ext, $dest_path, $sep = "_", $i = 0)
    {
        $temp_name = $i > 0 ? $filename . $sep . $i : $filename;

        if (file_exists($dest_path . $temp_name . "." . $ext))
        {
            return self::getAutoincreamentFileName($filename, $ext, $dest_path, $sep, $i + 1);
        }
        else
        {
            return $temp_name . "." . $ext;
        }
    }

    public static function createFolder($path)
    {
        if (!file_exists($path))
        {
            if (!mkdir($path, 0777, TRUE))
            {
                return false;
            }
        }
        return true;
    }

    public static function deleteAll($path, $exts = array(), $recursive = false)
    {
        $path_files = self::getFileList($path, $exts, $recursive);

        foreach ($path_files as $path => $files)
        {
            foreach ($files as $file)
            {
                if (!unlink($path . $file))
                {
                    return false;
                }
            }
        }

        return true;
    }

    public static function getFileList($path, $exts = array(), $recursive = false)
    {
        $files = scandir($path);

        foreach ($files as $k => $file)
        {
            $f = $path . $file . "/";

            if ($file == '.' || $file == '..')
            {
                unset($files[$k]);
            }
            else if ($recursive && is_dir($f))
            {
                $files[$f] = self::getFileList($f, $exts, $recursive);
            }
            else if (!empty($exts) && !in_array(pathinfo($file, PATHINFO_EXTENSION), $exts))
            {
                unset($files[$k]);
            }
        }

        return $files;
    }

    public static function read($file)
    {
        $file = explode("?", $file)[0];
        
        if (self::use_s3)
        {
            $aws = new AWSFileUtility();
            $result = $aws->read($file);
            
            if ($result == false)
            {
                return false;
            }
            
            return array(
                "filename" => pathinfo($file, PATHINFO_BASENAME),
                "content_type" => $result["ContentType"],
                "content" => $result["Body"]
            );
        }
        else
        {
            if (!file_exists($file))
            {
                return false;
            }
            
            return array(
                "filename" => pathinfo($file, PATHINFO_BASENAME),
                "content_type" => mime_content_type($file),
                "content" => file_get_contents($file)
            );
        }
    }
    
    public static function get($file)
    {
        $file = trim($file);
        if (!$file)
        {
            return SITE_URL . "img/dummy.jpg";
        }
        
        if (file_exists($file))
        {
            return SITE_URL . $file;
        }
        else if (self::use_s3)
        {
            return AWSFileUtility::get($file);
        }
        else
        {
            return SITE_URL . "img/dummy.jpg";
        }
    }
    
    public static function delete($file)
    {
        $file = explode("?", $file)[0];
        
        if (self::use_s3)
        {
            $aws = new AWSFileUtility();
            return $aws->delete($file);
        }
        else
        {
            if ( file_exists($file) )
            {
                return unlink($file);
            }
        }
        
        return true;
    }
}