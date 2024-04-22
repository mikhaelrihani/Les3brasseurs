<?php

namespace App\Controller\Api\User;

use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class JobController extends AbstractController
{
    #[Route('/api/user/job', name: 'app_api_user_job')]
    public function getJobs(JobRepository $jobRepository): JsonResponse
    {
        $jobs = $jobRepository->findAll();
        return $this->json([
           "jobs" => $jobs,
        ]);
    }

    #[Route('/api/user/job/{id}', name: 'app_api_user_job_show')]
    public function getJob(int $id, JobRepository $jobRepository): JsonResponse
    {
        $job = $jobRepository->find($id);
        return $this->json([
            "job" => $job,
        ]);
    }
}
