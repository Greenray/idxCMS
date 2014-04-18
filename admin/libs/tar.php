<?php
# idxCMS version 2.2
# Module BACKUP - Administration - Archiver tar API
# Copyright (C) 2002 Josh Barger <joshb@npt.com>
# Copyright (c) 2012-2014 Greenray greenray.spb@gmail.com

/**
 * TAR class
 * Processing backups
 */
class TAR {

    /**
     * Filename of the existing archive
     * @var string $filename
     */
    public $filename;

    /**
     * Name of the tar file
     * @var string $tarFile
     */
    public $tarFile;

    /**
     * Directories to backup
     * @var array $dirs
     */
    private $dirs;

    /**
     * Files to backup
     * @var array $files
     */
    private $files;

    /**
     * Directories to exclude
     * @var array $exclude_dirs
     */
    private $exclude_dirs = array();
    /**
     * Files to exclude
     * @var array $exclude
     */
    private $exclude_files = array('arj','avi','bzip','bzip2','gz','gzip','mp3','mov','mpeg','rar','tar','wmv','zip');

    /**
     * Number of directories
     * @var integer $numDirs
     */
    private $numDirs;

    /**
     * Number of files
     * @var integer $numFiles
     */
    private $numFiles;

    /**
     * Base directory
     * @var string
     */
    private $baseDir = 'content';

    /**
     * Is file is gzipped?
     * @var boolean $isGzipped
     */
    private $isGzipped;

    /** Class constructor */
    function __construct() {
        return TRUE;
    }

    /**
     * Computes the unsigned checksum of a file's header to try to ensure valid file
     * @param string $bytestring String for checksum
     * @return integer Checksum
     */
    function __computeUnsignedChecksum($bytestring) {
        $chksum = '';
        for ($i = 0; $i < 512; $i++) {
            $chksum += ord($bytestring[$i]);
        }
        for ($i = 0; $i < 8; $i++) {
            $chksum -= ord($bytestring[148 + $i]);
        }
        $chksum += ord(" ") * 8;
        return $chksum;
    }

    /**
     * Converts a NULL padded string to a non-NULL padded string
     * @param  string $string String to convert
     * @return string Converted string
     */
    function __parseNullPaddedString($string) {
        $position = strpos($string, chr(0));
        return substr($string, 0, $position);
    }

    /**
     * This function parses the current TAR file
     * @return boolean Always TRUE
     */
    function __parseTar() {
        // Read Files from archive
        $tar_length     = strlen($this->tarFile);
        $main_offset    = 0;
        $this->numFiles = 0;
        while ($main_offset < $tar_length) {
            // If we read a block of 512 nulls, we are at the end of the archive
            if (substr($this->tarFile, $main_offset, 512) == str_repeat(chr(0), 512)) {
                break;
            }
            // Parse file name
            $file_name   = $this->__parseNullPaddedString(substr($this->tarFile, $main_offset, 100));
            $file_mode   = substr($this->tarFile, $main_offset + 100, 8);          // File mode
            $file_uid    = octdec(substr($this->tarFile, $main_offset + 108, 8));  // File user ID
            $file_gid    = octdec(substr($this->tarFile, $main_offset + 116, 8));  // File group ID
            $file_size   = octdec(substr($this->tarFile, $main_offset + 124, 12)); // File size
            $file_time   = octdec(substr($this->tarFile, $main_offset + 136, 12)); // File update time - unix timestamp format
            $file_chksum = octdec(substr($this->tarFile, $main_offset + 148, 6));  // Checksum
            $file_type   = substr($this->tarFile, $main_offset + 156, 1);          // Directory or file
            $file_uname  = $this->__parseNullPaddedString(substr($this->tarFile, $main_offset + 265, 32)); // Owner name
            $file_gname  = $this->__parseNullPaddedString(substr($this->tarFile, $main_offset + 297, 32)); // Owner group
            // Make sure file is valid
            if ($this->__computeUnsignedChecksum(substr($this->tarFile, $main_offset, 512)) !== $file_chksum) {
                return FALSE;
            }
            $file_contents = substr($this->tarFile, $main_offset + 512, $file_size);   // File Contents
            if ($file_type === '5') {
                $this->numDirs++;
                // Create a new directory in archive array
                $activeDir = &$this->dirs[];
                // Assign values
                $activeDir["name"]       = $file_name;
                $activeDir["mode"]       = $file_mode;
                $activeDir["time"]       = $file_time;
                $activeDir["user_id"]    = $file_uid;
                $activeDir["group_id"]   = $file_gid;
                $activeDir["user_name"]  = $file_uname;
                $activeDir["group_name"] = $file_gname;
                $activeDir["checksum"]   = $file_chksum;
            } else {
                $this->numFiles++;
                // Create a new file in archive array
                $activeFile = &$this->files[];
                $activeFile["name"]       = $file_name;
                $activeFile["mode"]       = $file_mode;
                $activeFile["size"]       = $file_size;
                $activeFile["time"]       = $file_time;
                $activeFile["user_id"]    = $file_uid;
                $activeFile["group_id"]   = $file_gid;
                $activeFile["user_name"]  = $file_uname;
                $activeFile["group_name"] = $file_gname;
                $activeFile["checksum"]   = $file_chksum;
                $activeFile["file"]       = $file_contents;
            }
            // Move blocks which has been processed
            $main_offset += 512 + (ceil($file_size / 512) * 512);
        }
        return TRUE;
    }

    /**
     * Read a non gzipped tar file in for processing
     * @param  string  $filename   Full filename
     * @return boolean Always TRUE
     */
    function __readTar($filename = '') {
        // Set the filename to load
        if (!$filename) $filename = $this->filename;
        // Read in the TAR file
        $this->tarFile = file_get_contents($filename);
        if ($this->tarFile[0] == chr(31) && $this->tarFile[1] == chr(139) && $this->tarFile[2] == chr(8)) {
            if (!function_exists("gzinflate")) return FALSE;
            $this->isGzipped = TRUE;
            $this->tarFile   = gzinflate(substr($this->tarFile, 10, -4));
        }
        // Parse the TAR file
        $this->__parseTar();
        return TRUE;
    }

    /**
     * Generates a TAR file from the processed data
     * @return boolean Always TRUE
     */
    function __generateTAR() {
        $this->tarFile = '';
        // Generate Records for each directory
        if ($this->numDirs > 0) {
            foreach($this->dirs as $key => $info) {
                // Generate tar header for this directory
                $header  = str_pad($info["name"], 100, chr(0));                              // Filename
                $header .= str_pad(decoct($info["mode"]),     7, "0", STR_PAD_LEFT).chr(0);  // Permissions
                $header .= str_pad(decoct($info["user_id"]),  7, "0", STR_PAD_LEFT).chr(0);  // UID
                $header .= str_pad(decoct($info["group_id"]), 7, "0", STR_PAD_LEFT).chr(0);  // GID
                $header .= str_pad(decoct(0), 11, "0", STR_PAD_LEFT).chr(0);                 // Size
                $header .= str_pad(decoct($info["time"]),    11, "0", STR_PAD_LEFT).chr(0);  // Time
                $header .= str_repeat(" ", 8);            // Checksum
                $header .= "5";                           // Type
                $header .= str_repeat(chr(0), 100);       // Linkname
                $header .= str_pad("ustar", 6, chr(32));  // Magic
                $header .= chr(32).chr(0);                // Version
                $header .= str_pad("", 32, chr(0));       // Username
                $header .= str_pad("", 32, chr(0));       // Groupname
                $header .= str_repeat(chr(0), 8);         // Devmajor
                $header .= str_repeat(chr(0), 8);         // Devminor
                $header .= str_repeat(chr(0), 155);       // Prefix
                $header .= str_repeat(chr(0), 12);        // End of header
                // Compute header checksum
                $checksum = str_pad(decoct($this->__computeUnsignedChecksum($header)), 6, "0", STR_PAD_LEFT);
                for ($i = 0; $i < 6; $i++) {
                    $header[(148 + $i)] = substr($checksum, $i, 1);
                }
                $header[154] = chr(0);
                $header[155] = chr(32);
                // Add new tar formatted data to tar file contents
                $this->tarFile .= $header;
            }
        }
        // Generate Records for each file
        if ($this->numFiles > 0) {
            foreach($this->files as $key => $info) {
                // Generate the TAR header for this file
                $header = str_pad($info['name'], 100, chr(0));                               // Filename
                $header .= str_pad(decoct($info['mode']),     7, "0", STR_PAD_LEFT).chr(0);  // Permissions
                $header .= str_pad(decoct($info['user_id']),  7, "0", STR_PAD_LEFT).chr(0);  // UID
                $header .= str_pad(decoct($info['group_id']), 7, "0", STR_PAD_LEFT).chr(0);  // GID
                $header .= str_pad(decoct($info['size']),    11, "0", STR_PAD_LEFT).chr(0);  // Size
                $header .= str_pad(decoct($info['time']),    11, "0", STR_PAD_LEFT).chr(0);  // Time
                $header .= str_repeat(" ", 8);                        // Checksum
                $header .= "0";                                       // Typeflag
                $header .= str_repeat(chr(0), 100);                   // Linkname
                $header .= str_pad("ustar", 6, chr(32));              // Magic
                $header .= chr(32).chr(0);                            // Version
                $header .= str_pad($info["user_name"], 32, chr(0));   // Username
                $header .= str_pad($inf["group_name"], 32, chr(0));   // Groupname
                $header .= str_repeat(chr(0), 8);                     // Devmajor
                $header .= str_repeat(chr(0), 8);                     // Drevminor
                $header .= str_repeat(chr(0), 155);                   // Prefix
                $header .= str_repeat(chr(0), 12);                    // End of header
                // Compute header checksum
                $checksum = str_pad(decoct($this->__computeUnsignedChecksum($header)), 6, "0", STR_PAD_LEFT);
                for ($i = 0; $i < 6; $i++) {
                    $header[(148 + $i)] = substr($checksum,$i,1);
                }
                $header[154] = chr(0);
                $header[155] = chr(32);
                // Pad file contents to byte count divisible by 512
                $file_contents = str_pad($info["file"], (ceil($info["size"] / 512) * 512), chr(0));
                // Add new tar formatted data to tar file contents
                $this->tarFile .= $header.$file_contents;
            }
        }
        // Add 512 bytes of NULLs to designate EOF
        $this->tarFile .= str_repeat(chr(0), 512);
        return TRUE;
    }

    /**
     * Open a TAR file
     * @param  string $filename Filename
     * @return boolean
     */
    function openTAR($filename) {
        // Clear any values from previous tar archives
        unset($this->filename);
        unset($this->isGzipped);
        unset($this->tarFile);
        unset($this->files);
        unset($this->dirs);
        unset($this->numFiles);
        unset($this->numDirs);
        // If the tar file doesn't exist...
        if (!file_exists($filename)) return FALSE;
        $this->filename = $filename;
        // Parse this file
        $this->__readTar();
        return TRUE;
    }

    /**
     * Appends a tar file to the end of the currently opened tar file
     * @param  string $filename Filename
     * @return boolean
     */
    function appendTar($filename) {
        if (!file_exists($filename)) return FALSE;
        $this->__readTar($filename);
        return TRUE;
    }

    /**
     * Retrieves information about a file in the current tar archive
     * @param  string $filename Filename
     * @return string FALSE on fail
     */
    function getFile($filename) {
        if ($this->numFiles > 0) {
            foreach ($this->files as $key => $info) {
                if ($info["name"] == $filename) {
                    return $info;
                }
            }
        }
        return FALSE;
    }

    /**
     * Retrieves information about a directory in the current tar archive
     * @param  string $dirname Directory name
     * @return string FALSE on fail
     */
    function getDirectory($dirname) {
        if ($this->numDirs > 0) {
            foreach ($this->dirs as $key => $info) {
                if ($info["name"] == $dirname) {
                    return $info;
                }
            }
        }
        return FALSE;
    }

    /**
     * Check if this tar archive contains a specific file
     * @param  string $filename Filename
     * @return boolean
     */
    function containsFile($filename) {
        if ($this->numFiles > 0) {
            foreach ($this->files as $key => $info) {
                if ($info["name"] == $filename) {
                	return TRUE;
                }
            }
        }
        return FALSE;
    }

    /**
     * Check if this tar archive contains a specific directory
     * @param  string $dirname Directory name
     * @return boolean
     */
    function containsDirectory($dirname) {
        if ($this->numDirs > 0) {
            foreach ($this->dirs as $key => $info) {
                if ($info["name"] == $dirname) {
                	return TRUE;
                }
            }
        }
        return FALSE;
    }

    function excludeDir($dir) {
        $this->exclude_dirs[] = $dir;
    }

    /**
     * Add a directory to archive
     * @param  string  $dirname Directory name
     * @param  boolean $recurse Add directory recursively ?
     * @return boolean
     */
    function addDirectory($dirname, $recurse = FALSE) {
        if (!file_exists($dirname)) return FALSE;
        clearstatcache();
        // Get directory information
        $file_info = stat($dirname);
        // Add directory to processed data
        $this->numDirs++;
        $activeDir             = &$this->dirs[];
        $activeDir["name"]     = $dirname;
        $activeDir["mode"]     = @$file_info["mode"];
        $activeDir["time"]     = @$file_info["mtime"];
        $activeDir["user_id"]  = @$file_info["uid"];
        $activeDir["group_id"] = @$file_info["gid"];
        $activeDir["checksum"] = 0;
        $files = GetFilesList($dirname);
        foreach ($files as $key => $file) {
            if (($file != '.') && ($file != '..') && (!in_array($this->baseDir.DS.$file, $this->exclude_dirs))) {
                if (is_file($dirname.DS.$file)) {
                	$this->addFile($dirname.DS.$file);
                } else {
                    if ($recurse) {
                        $this->addDirectory($dirname.DS.$file, TRUE);
                    }
                }
            }
        }
        return TRUE;
    }

    /**
     * Add a file to the tar archive
     * @param  string  $filename Backup filename
     * @param  boolean $binary   Binary file?
     * @return boolean
     */
    function addFile($filename, $binary = FALSE) {
        if (!file_exists($filename)) return FALSE;
        // Make sure there are no other files in the archive that have this same filename
        if ($this->containsFile($filename)) return FALSE;
        $path_parts = pathinfo($filename);
        if (!empty($path_parts['extension'])) {
            if (in_array($path_parts['extension'], $this->exclude_files)) {
                return FALSE;
            }
        }
        clearstatcache();
        // Get file information
        $file_info = stat($filename);
        // Read in the file's contents
        $fp = (!$binary) ? fopen($filename, "r") : fopen($filename, "rb");
        $file_contents = (filesize($filename) !== 0) ? fread($fp, filesize($filename)) : '';
        fclose($fp);
        // Add file to processed data
        $this->numFiles++;
        $activeFile               = &$this->files[];
        $activeFile["name"]       = $filename;
        $activeFile["mode"]       = $file_info["mode"];
        $activeFile["user_id"]    = $file_info["uid"];
        $activeFile["group_id"]   = $file_info["gid"];
        $activeFile["size"]       = $file_info["size"];
        $activeFile["time"]       = $file_info["mtime"];
        $activeFile["checksum"]   = isset($checksum) ? $checksum : '';
        $activeFile["user_name"]  = '';
        $activeFile["group_name"] = '';
        $activeFile["file"]       = trim($file_contents);
        return TRUE;
    }

    /**
     * Remove a directory from the tar archive
     * @param  string $dirname Directory name
     * @return boolean
     */
    function removeDirectory($dirname) {
    	if ($this->numDirs > 0) {
    		foreach ($this->dirs as $key => $info) {
    			if ($info["name"] == $dirname) {
    				$this->numDirs--;
    				unset($this->dirs[$key]);
    				return TRUE;
    			}
    		}
    	}
    	return FALSE;
    }

    /**
     * Remove a file from the tar archive
     * @param  string  $filename Filename
     * @return boolean
     */
    function removeFile($filename) {
        if ($this->numFiles > 0) {
            foreach ($this->files as $key => $info) {
                if ($info["name"] == $filename) {
                    $this->numFiles--;
                    unset($this->files[$key]);
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    /**
     * Write the currently loaded tar archive to disk
     * @return boolean
     */
    function saveTar() {
        if (!$this->filename) return FALSE;
        // Write tar to current file using specified gzip compression
        $this->toTar($this->filename, $this->isGzipped);
        return TRUE;
    }

    /**
     * Saves tar archive to a different file than the current file
     * @param  string  $filename Filename
     * @param  boolean $useGzip  Use GZ compression?
     * @return boolean
     */
    function toTar($filename, $useGzip) {
        if (!$filename) return FALSE;
        // Encode processed files into TAR file format
        $this->__generateTar();
        // GZ Compress the data if we need to
        if ($useGzip) {
            // Make sure we have gzip support
            if (!function_exists("gzencode")) {
                return FALSE;
            }
            $file = gzencode($this->tarFile);
        } else {
            $file = $this->tarFile;
        }
        // Write the TAR file
        return file_put_contents($filename, $file);
    }

    /**
     * Sends tar archive to stdout
     * @param  string  $filename Filename
     * @param  boolean $useGzip  Use GZ compression?
     * @return string
     */
    function toTarOutput($filename, $useGzip) {
        if (!$filename) return FALSE;
        // Encode processed files into TAR file format
        $this->__generateTar();
        // GZ Compress the data if we need to
        if ($useGzip) {
            // Make sure we have gzip support
            if (!function_exists("gzencode")) {
                return FALSE;
            }
            $file = gzencode($this->tarFile);
        } else {
            $file = $this->tarFile;
        }
        return $file;
    }
}
?>