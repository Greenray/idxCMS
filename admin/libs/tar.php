<?php
# idxCMS version 2.1
# Copyright (c) 2012 Greenray greenray.spb@gmail.com
#
# Module BACKUP - Administration - Archiver tar API
# Author: Original: Josh Barger <joshb@npt.com>
#         Upgrade:  Greenray <greenray.spb@.gmail.com>

/**
 * TAR class
 * Processing backups.
 * @author Josh Barger <joshb@npt.com>
 */
class TAR {

    /**
     * Filename.
     * @var string $filename
     */
    public $filename;

    /**
     * Is file is gzipped?
     * @var boolean $isGzipped
     */
    public $isGzipped;

    /**
     * Name of the tar fileю
     * @var string $tar_file
     */
    public $tar_file;

    /**
     * Files to backup.
     * @var array $files
     */
    public $files;

    /**
     * Directories to backup
     * @var array $directories
     */
    public $directories;

    /**
     * Directories to exclude.
     * @var array $exclude
     */
    public $exclude = array('content/temp');
    private $exclude_files = array('asf','arj','avi','bzip','bzip2','gz','gzip','mp3','mov','mpeg','rar','tar','wmv','zip');
    /**
     * Number of filesю
     * @var integer $numFiles
     */
    public $numFiles;

    /**
     * Number of directories.
     * @var integer $numDirectories
     */
    public $numDirectories;

    /** Class constructor */
    function __construct() {
        return TRUE;
    }

    /**
     * Computes the unsigned Checksum of a file's header to try to ensure valid file.
     * @param string $bytestring String for checksum
     */
    function __computeUnsignedChecksum($bytestring) {
        $unsigned_chksum = '';
        for ($i = 0; $i < 512; $i++)
            $unsigned_chksum += ord($bytestring[$i]);
        for ($i = 0; $i < 8; $i++)
            $unsigned_chksum -= ord($bytestring[148 + $i]);
        $unsigned_chksum += ord(" ") * 8;
        return $unsigned_chksum;
    }

    /**
     * Converts a NULL padded string to a non-NULL padded string.
     * @param  string $string String to convert
     * @return string
     */
    function __parseNullPaddedString($string) {
        $position = strpos($string,chr(0));
        return substr($string, 0, $position);
    }

    /**
     * This function parses the current TAR file.
     * @return boolean Always TRUE
     */
    function __parseTar() {
        // Read Files from archive
        $tar_length = strlen($this->tar_file);
        $main_offset = 0;
        $this->numFiles = 0;
        while ($main_offset < $tar_length) {
            // If we read a block of 512 nulls, we are at the end of the archive
            if (substr($this->tar_file, $main_offset, 512) == str_repeat(chr(0), 512))
                break;
            // Parse file name
            $file_name   = $this->__parseNullPaddedString(substr($this->tar_file, $main_offset, 100));
            $file_mode   = substr($this->tar_file, $main_offset + 100, 8);          // File mode
            $file_uid    = octdec(substr($this->tar_file, $main_offset + 108, 8));  // File user ID
            $file_gid    = octdec(substr($this->tar_file, $main_offset + 116, 8));  // File group ID
            $file_size   = octdec(substr($this->tar_file, $main_offset + 124, 12)); // File size
            $file_time   = octdec(substr($this->tar_file, $main_offset + 136, 12)); // File update time - unix timestamp format
            $file_chksum = octdec(substr($this->tar_file, $main_offset + 148, 6));  // Checksum
            $file_type   = substr($this->tar_file, $main_offset + 156, 1);          // Directory or file
            $file_uname  = $this->__parseNullPaddedString(substr($this->tar_file, $main_offset + 265, 32)); // Owner name
            $file_gname  = $this->__parseNullPaddedString(substr($this->tar_file, $main_offset + 297, 32)); // Owner group
            // Make sure our file is valid
            if ($this->__computeUnsignedChecksum(substr($this->tar_file, $main_offset, 512)) != $file_chksum)
                return FALSE;
            $file_contents = substr($this->tar_file, $main_offset + 512, $file_size);   // File Contents
            if ($file_type === '5') {
                $this->numDirectories++;
                // Create a new directory in our array
                $activeDir = &$this->directories[];
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
                // Create us a new file in our array
                $activeFile = &$this->files[];
                // Asign Values
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
            // Move our offset the number of blocks we have processed
            $main_offset += 512 + (ceil($file_size / 512) * 512);
        }
        return TRUE;
    }

    /**
     * Read a non gzipped tar file in for processing.
     * @param  string  $filename   Full filename
     * @return boolean Always TRUE
     */
    function __readTar($filename = '') {
        // Set the filename to load
        if (!$filename) $filename = $this->filename;
        // Read in the TAR file
        $this->tar_file = file_get_contents($filename);
        if ($this->tar_file[0] == chr(31) && $this->tar_file[1] == chr(139) && $this->tar_file[2] == chr(8)) {
            if (!function_exists("gzinflate")) return FALSE;
            $this->isGzipped = TRUE;
            $this->tar_file  = gzinflate(substr($this->tar_file, 10, -4));
        }
        // Parse the TAR file
        $this->__parseTar();
        return TRUE;
    }

    /**
     * Generates a TAR file from the processed data.
     * @return boolean Always TRUE
     */
    function __generateTAR() {
        $this->tar_file = '';
        // Generate Records for each directory, if we have directories
        if ($this->numDirectories > 0) {
            foreach($this->directories as $key => $information) {
                // Generate tar header for this directory
                // Filename, Permissions, UID, GID, size, Time, checksum, typeflag, linkname, magic, version, user name, group name, devmajor, devminor, prefix, end
                $header  = str_pad($information["name"], 100, chr(0));
                $header .= str_pad(decoct($information["mode"]), 7, "0", STR_PAD_LEFT).chr(0);
                $header .= str_pad(decoct($information["user_id"]), 7, "0", STR_PAD_LEFT).chr(0);
                $header .= str_pad(decoct($information["group_id"]), 7, "0", STR_PAD_LEFT).chr(0);
                $header .= str_pad(decoct(0), 11, "0", STR_PAD_LEFT).chr(0);
                $header .= str_pad(decoct($information["time"]), 11, "0", STR_PAD_LEFT).chr(0);
                $header .= str_repeat(" ", 8);
                $header .= "5";
                $header .= str_repeat(chr(0), 100);
                $header .= str_pad("ustar", 6, chr(32));
                $header .= chr(32).chr(0);
                $header .= str_pad("", 32, chr(0));
                $header .= str_pad("", 32, chr(0));
                $header .= str_repeat(chr(0), 8);
                $header .= str_repeat(chr(0), 8);
                $header .= str_repeat(chr(0), 155);
                $header .= str_repeat(chr(0), 12);
                // Compute header checksum
                $checksum = str_pad(decoct($this->__computeUnsignedChecksum($header)), 6, "0", STR_PAD_LEFT);
                for ($i = 0; $i < 6; $i++) {
                    $header[(148 + $i)] = substr($checksum, $i, 1);
                }
                $header[154] = chr(0);
                $header[155] = chr(32);
                // Add new tar formatted data to tar file contents
                $this->tar_file .= $header;
            }
        }
        // Generate Records for each file, if we have files (We should...)
        if ($this->numFiles > 0) {
            foreach($this->files as $key => $information) {
                // Generate the TAR header for this file
                // Filename, Permissions, UID, GID, size, Time, checksum, typeflag, linkname, magic, version, user name, group name, devmajor, devminor, prefix, end
                $header = str_pad($information['name'], 100, chr(0));
                $header .= str_pad(decoct($information['mode']), 7, "0", STR_PAD_LEFT).chr(0);
                $header .= str_pad(decoct($information['user_id']), 7, "0", STR_PAD_LEFT).chr(0);
                $header .= str_pad(decoct($information['group_id']), 7, "0", STR_PAD_LEFT).chr(0);
                $header .= str_pad(decoct($information['size']), 11, "0", STR_PAD_LEFT).chr(0);
                $header .= str_pad(decoct($information['time']), 11, "0", STR_PAD_LEFT).chr(0);
                $header .= str_repeat(" ", 8);
                $header .= "0";
                $header .= str_repeat(chr(0), 100);
                $header .= str_pad("ustar", 6, chr(32));
                $header .= chr(32).chr(0);
                $header .= str_pad($information["user_name"], 32, chr(0));   // How do I get a file's user name from PHP?
                $header .= str_pad($information["group_name"],32, chr(0));   // How do I get a file's group name from PHP?
                $header .= str_repeat(chr(0), 8);
                $header .= str_repeat(chr(0), 8);
                $header .= str_repeat(chr(0), 155);
                $header .= str_repeat(chr(0), 12);
                // Compute header checksum
                $checksum = str_pad(decoct($this->__computeUnsignedChecksum($header)), 6, "0", STR_PAD_LEFT);
                for ($i = 0; $i < 6; $i++) {
                    $header[(148 + $i)] = substr($checksum,$i,1);
                }
                $header[154] = chr(0);
                $header[155] = chr(32);
                // Pad file contents to byte count divisible by 512
                $file_contents = str_pad($information["file"], (ceil($information["size"] / 512) * 512), chr(0));
                // Add new tar formatted data to tar file contents
                $this->tar_file .= $header.$file_contents;
            }
        }
        // Add 512 bytes of NULLs to designate EOF
        $this->tar_file .= str_repeat(chr(0), 512);
        return TRUE;
    }

    /**
     * Open a TAR file.
     * @param  string $filename Filename
     * @return boolean
     */
    function openTAR($filename) {
        // Clear any values from previous tar archives
        unset($this->filename);
        unset($this->isGzipped);
        unset($this->tar_file);
        unset($this->files);
        unset($this->directories);
        unset($this->numFiles);
        unset($this->numDirectories);
        // If the tar file doesn't exist...
        if (!file_exists($filename)) return FALSE;
        $this->filename = $filename;
        // Parse this file
        $this->__readTar();
        return TRUE;
    }

    /**
     * Appends a tar file to the end of the currently opened tar file.
     * @param  string $filename Filename
     * @return boolean
     */
    function appendTar($filename) {
        if (!file_exists($filename)) return FALSE;
        $this->__readTar($filename);
        return TRUE;
    }

    /**
     * Retrieves information about a file in the current tar archive.
     * @param  string $filename Filename
     * @return string FALSE on fail
     */
    function getFile($filename) {
        if ($this->numFiles > 0) {
            foreach ($this->files as $key => $information) {
                if ($information["name"] == $filename)
                    return $information;
            }
        }
        return FALSE;
    }

    /**
     * Retrieves information about a directory in the current tar archive.
     * @param  string $dirname Directory name
     * @return string FALSE on fail
     */
    function getDirectory($dirname) {
        if ($this->numDirectories > 0) {
            foreach ($this->directories as $key => $information) {
                if ($information["name"] == $dirname)
                    return $information;
            }
        }
        return FALSE;
    }

    /**
     * Check if this tar archive contains a specific file.
     * @param  string $filename Filename
     * @return boolean
     */
    function containsFile($filename) {
        if ($this->numFiles > 0) {
            foreach ($this->files as $key => $information) {
                if ($information["name"] == $filename) return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * Check if this tar archive contains a specific directory.
     * @param  string $dirname Directory name
     * @return boolean
     */
    function containsDirectory($dirname) {
        if ($this->numDirectories > 0) {
            foreach ($this->directories as $key => $information) {
                if ($information["name"] == $dirname) return TRUE;
            }
        }
        return FALSE;
    }

    function excludeDir($dir) {
        $this->exclude[] = $dir;
    }

    /**
     * Add a directory to this tar archive.
     * @param  string  $dirname Directory name
     * @param  boolean $recurse Add directory recursively ?
     * @return boolean
     */
    function addDirectory($dirname, $recurse = FALSE) {
        if (!file_exists($dirname)) return FALSE;
        clearstatcache();
        // Get directory information
        $file_information = stat($dirname);
        // Add directory to processed data
        $this->numDirectories++;
        $activeDir             = &$this->directories[];
        $activeDir["name"]     = $dirname;
        $activeDir["mode"]     = @$file_information["mode"];
        $activeDir["time"]     = @$file_information["mtime"];
        $activeDir["user_id"]  = @$file_information["uid"];
        $activeDir["group_id"] = @$file_information["gid"];
        $activeDir["checksum"] = 0;
        $files = GetFilesList($dirname);
        foreach ($files as $key => $file) {
            if (($file != '.') && ($file != '..') && (!in_array('content/'.$file, $this->exclude))) {
                if (is_file($dirname.'/'.$file)) $this->addFile($dirname.'/'.$file);
                else {
                    if ($recurse) {
                        $this->addDirectory($dirname.'/'.$file, TRUE);
                    }
                }
            }
        }
        return TRUE;
    }

    /**
     * Add a file to the tar archive.
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
            if (in_array($path_parts['extension'], $this->exclude_files))
                return FALSE;
        }
        clearstatcache();
        // Get file information
        $file_information = stat($filename);
        // Read in the file's contents
        $fp = (!$binary) ? fopen($filename, "r") : fopen($filename, "rb");
        $file_contents = (filesize($filename) !== 0) ? fread($fp, filesize($filename)) : '';
        fclose($fp);
        // Add file to processed data
        $this->numFiles++;
        $activeFile               = &$this->files[];
        $activeFile["name"]       = $filename;
        $activeFile["mode"]       = $file_information["mode"];
        $activeFile["user_id"]    = $file_information["uid"];
        $activeFile["group_id"]   = $file_information["gid"];
        $activeFile["size"]       = $file_information["size"];
        $activeFile["time"]       = $file_information["mtime"];
        $activeFile["checksum"]   = isset($checksum) ? $checksum : '';
        $activeFile["user_name"]  = "";
        $activeFile["group_name"] = "";
        $activeFile["file"]       = trim($file_contents);
        return TRUE;
    }

    /**
     * Remove a file from the tar archive.
     * @param  string  $filename Filename
     * @return boolean
     */
    function removeFile($filename) {
        if ($this->numFiles > 0) {
            foreach ($this->files as $key => $information) {
                if ($information["name"] == $filename) {
                    $this->numFiles--;
                    unset($this->files[$key]);
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    /**
     * Remove a directory from the tar archive.
     * @param  string $dirname Directory name
     * @return boolean
     */
    function removeDirectory($dirname) {
        if ($this->numDirectories > 0) {
            foreach ($this->directories as $key => $information ) {
                if ($information["name"] == $dirname) {
                    $this->numDirectories--;
                    unset($this->directories[$key]);
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    /**
     * Write the currently loaded tar archive to disk.
     * @return boolean
     */
    function saveTar() {
        if (!$this->filename) return FALSE;
        // Write tar to current file using specified gzip compression
        $this->toTar($this->filename, $this->isGzipped);
        return TRUE;
    }

    /**
     * Saves tar archive to a different file than the current file.
     * @param  string  $filename Filename
     * @param  boolean $useGzip  Use GZ compression?
     * @return boolean
     */
    function toTar($filename, $useGzip) {
        if (!$filename) return FALSE;
        // Encode processed files into TAR file format
        $this->__generateTar();
        // GZ Compress the data if we need to
        if ( $useGzip ) {
            // Make sure we have gzip support
            if (!function_exists("gzencode")) {
                return FALSE;
            }
            $file = gzencode($this->tar_file);
        } else {
            $file = $this->tar_file;
        }
        // Write the TAR file
        return file_put_contents($filename, $file);
    }

    /**
     * Sends tar archive to stdout.
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
            $file = gzencode($this->tar_file);
        } else {
            $file = $this->tar_file;
        }
        return $file;
    }
}
?>