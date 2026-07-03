<?php

namespace App\Domains\Organization\Services;

use App\Models\Department;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DepartmentService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function list(Request $request): LengthAwarePaginator
    {
        return Department::search($request, ['name', 'code'])
            ->where('branch_id', $request->user()->branch_id)
            ->paginate($request->input('per_page', 15));
    }

    public function create(array $data): Department
    {
        $department = Department::create($data);

        $this->auditLogger->log(
            'department.created',
            $department,
            null,
            $department->toArray()
        );

        return $department;
    }

    public function update(Department $department, array $data): Department
    {
        $oldValues = $department->toArray();
        $department->update($data);

        $this->auditLogger->log(
            'department.updated',
            $department,
            $oldValues,
            $department->toArray()
        );

        return $department;
    }

    public function delete(Department $department): void
    {
        $department->delete();

        $this->auditLogger->log(
            'department.deleted',
            $department
        );
    }
}
