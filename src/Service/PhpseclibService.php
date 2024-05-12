<?php

namespace App\Service;

use phpseclib3\Net\SFTP;


class PhpseclibService
{
    protected $fileUploadDirectory;

    public function __construct($fileUploadDirectory)
    {
        $this->fileUploadDirectory = $fileUploadDirectory;
    }
    public function authenticate()
    {
        $host = '193.203.191.6';
        $port = 22;
        $username = 'root';
        $password = 'Rihani29!';
        $sftp = $this->connectSFTP($host, $port, $username, $password);
        return $sftp ;
    }
    public function connectSFTP($host, $port, $username, $password)
    {
        $sftp = new SFTP($host, $port);
        if (!$sftp->login($username, $password)) {
            throw new \Exception("Login failed");
        }
        return $sftp;
    }

    public function uploadFile($sftp, $localFile, $remoteFile)
    {
        if (!$sftp->put($remoteFile, $localFile, SFTP::SOURCE_LOCAL_FILE)) {
            throw new \Exception("Upload failed");
        }
    }

    public function downloadFile($sftp, $remoteFile, $localFile)
    {
        if (!$sftp->get($remoteFile, $localFile)) {
            throw new \Exception("Download failed");
        }
    }

    public function listFiles($sftp, $directory)
    {
        $files = $sftp->nlist($directory);
        return $files;
    }

    public function deleteFile($sftp, $file)
    {
        if (!$sftp->delete($file)) {
            throw new \Exception("Delete failed");
        }
    }
    public function getFileUploadDirectory()
    {
        return $this->fileUploadDirectory;
    }
}