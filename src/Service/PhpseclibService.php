<?php

namespace App\Service;

use phpseclib3\Net\SFTP;


class PhpseclibService
{
    protected $remoteDirectory;
    protected $fileDownloadDirectory;
    protected $sftp;


    public function __construct(string $fileDownloadDirectory, string $host, string $username, string $password, string $remoteDirectory)
    {
        $this->sftp = new SFTP($host);
        if (!$this->sftp->login($username, $password)) {
            throw new \Exception('Login failed');
        }
        $this->remoteDirectory = $remoteDirectory;
        $this->fileDownloadDirectory = $fileDownloadDirectory;

    }

    public function uploadFile($localFile, $remoteFile)
    {
        if (!$this->sftp->put($remoteFile, $localFile, SFTP::SOURCE_LOCAL_FILE)) {
            throw new \Exception("Upload failed");
        }
    }

    public function downloadFile($remoteFile, $localFile)
    {
        if (!$this->sftp->get($remoteFile, $localFile)) {
            throw new \Exception("Download failed");
        }
    }

    public function listFiles(): array
    {
        return $this->sftp->nlist($this->remoteDirectory);
    }

    public function deleteFile($file)
    {
        if (!$this->sftp->delete($file)) {
            throw new \Exception("Delete failed");
        }
    }
    public function getFileUploadDirectory()
    {
        return $this->remoteDirectory;
    }
    public function getFileDownloadDirectory()
    {
        return $this->fileDownloadDirectory;
    }
}