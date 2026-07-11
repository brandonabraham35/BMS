<?php

namespace App\Domains\Organization\Services;

use App\Models\Company;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CompanyService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function list(Request $request): LengthAwarePaginator
    {
        return Company::search($request, ['name', 'legal_name', 'email'])
            ->where('workspace_id', $request->user()->workspace_id)
            ->paginate($request->input('per_page', 15));
    }

    public function create(array $data): Company
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $company = Company::create($data);

        $this->auditLogger->log(
            'company.created',
            $company,
            null,
            $company->toArray()
        );

        event(new \App\Domains\Organization\Events\CompanyUpdated($company->id));

        return $company;
    }

    public function update(Company $company, array $data): Company
    {
        $oldValues = $company->toArray();
        $company->update($data);

        $this->auditLogger->log(
            'company.updated',
            $company,
            $oldValues,
            $company->toArray()
        );

        return $company;
    }

    public function delete(Company $company): void
    {
        $company->delete();

        $this->auditLogger->log(
            'company.deleted',
            $company
        );

        event(new \App\Domains\Organization\Events\CompanyUpdated($company->id));
    }

    public function restore(string $id): Company
    {
        $company = Company::withTrashed()->findOrFail($id);
        $company->restore();

        $this->auditLogger->log(
            'company.restored',
            $company
        );

        event(new \App\Domains\Organization\Events\CompanyUpdated($company->id));

        return $company;
    }

    public function forceDelete(string $id): void
    {
        $company = Company::withTrashed()->findOrFail($id);

        $this->auditLogger->log(
            'company.permanently_deleted',
            $company
        );

        $company->forceDelete();
    }
}
