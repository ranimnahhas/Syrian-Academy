<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Repositories\EnrollmentRequestRepository;
use Illuminate\Support\Str;

class EnrollmentRequestService
{
    public function __construct(
        private EnrollmentRequestRepository $enrollmentRequestRepository
    ) {}

    public function getAll()
    {
        return $this->enrollmentRequestRepository->getWithDetails();
    }

    public function getById(int $id)
    {
        return $this->enrollmentRequestRepository->find($id, ['user', 'course', 'confirmer']);
    }

    public function getByUser(int $userId)
    {
        return $this->enrollmentRequestRepository->getByUser($userId);
    }

    public function create(array $data, int $userId)
    {
        if ($this->enrollmentRequestRepository->checkExisting($userId, $data['course_id'])) {
            return null;
        }

        $data['user_id'] = $userId;
        $data['status'] = 'pending';

        return $this->enrollmentRequestRepository->create($data);
    }

    public function approve(int $id, int $adminId)
    {
        $enrollmentRequest = $this->enrollmentRequestRepository->find($id);

        if (!$enrollmentRequest || $enrollmentRequest->status !== 'pending') {
            return null;
        }

        $data = [
            'confirmed_by' => $adminId,
            'paid_at'      => now(),
        ];

        // كورس مدفوع → كود تفعيل
        if ($enrollmentRequest->course->is_paid) {
            $data['status'] = 'paid';
            $data['payment_code'] = $this->generateCode();
            $data['code_generated_at'] = now();
            $data['code_expires_at'] = now()->addDays(30);
        } else {
            // كورس مجاني → تفعيل مباشر + إنشاء Enrollment
            $data['status'] = 'active';
            $data['code_used_at'] = now();

            $this->createEnrollment($enrollmentRequest);
        }

        $enrollmentRequest->update($data);

        return $enrollmentRequest->fresh(['user', 'course', 'confirmer']);
    }

    public function reject(int $id, int $adminId, ?string $reason = null)
    {
        $enrollmentRequest = $this->enrollmentRequestRepository->find($id);

        if (!$enrollmentRequest || $enrollmentRequest->status !== 'pending') {
            return null;
        }

        $enrollmentRequest->update([
            'status'          => 'rejected',
            'rejected_reason' => $reason,
            'confirmed_by'    => $adminId,
        ]);

        return $enrollmentRequest->fresh(['user', 'course', 'confirmer']);
    }

    public function regenerateCode(int $id)
    {
        $enrollmentRequest = $this->enrollmentRequestRepository->find($id);

        if (!$enrollmentRequest || !in_array($enrollmentRequest->status, ['paid', 'active'])) {
            return null;
        }

        $enrollmentRequest->update([
            'payment_code'      => $this->generateCode(),
            'code_generated_at' => now(),
            'code_expires_at'   => now()->addDays(30),
        ]);

        return $enrollmentRequest->fresh();
    }

    public function cancelCode(int $id)
    {
        $enrollmentRequest = $this->enrollmentRequestRepository->find($id);

        if (!$enrollmentRequest || !in_array($enrollmentRequest->status, ['paid', 'active'])) {
            return null;
        }

        $enrollmentRequest->update([
            'payment_code'      => null,
            'code_generated_at' => null,
            'code_expires_at'   => null,
            'status'            => 'expired',
        ]);

        return $enrollmentRequest->fresh();
    }

    // طالب يدخل كود التفعيل
   public function activate(string $code, int $userId)
{
    $enrollmentRequest = $this->enrollmentRequestRepository->findByCode($code, $userId);

    if (!$enrollmentRequest) {
        return null;
    }

    $enrollmentRequest->update([
        'status'       => 'active',
        'code_used_at' => now(),
    ]);

    $this->createEnrollment($enrollmentRequest);

    return $enrollmentRequest->fresh(['user', 'course']);
}

    private function createEnrollment($enrollmentRequest): void
    {
        Enrollment::create([
            'user_id'                => $enrollmentRequest->user_id,
            'course_id'              => $enrollmentRequest->course_id,
            'enrollment_request_id'  => $enrollmentRequest->id,
            'enrolled_at'            => now(),
            'is_active'              => true,
        ]);
    }

    private function generateCode(): string
    {
        return strtoupper(Str::random(10));
    }
}