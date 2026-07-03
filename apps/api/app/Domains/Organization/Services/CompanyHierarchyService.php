<?php

namespace App\Domains\Organization\Services;

use App\Models\Company;
use Illuminate\Support\Collection;

class CompanyHierarchyService
{
    /**
     * Get the full ancestry of a company.
     */
    public function getAncestors(Company $company): Collection
    {
        $ancestors = collect();
        $parent = $company->parent;

        while ($parent) {
            $ancestors->push($parent);
            $parent = $parent->parent;
        }

        return $ancestors;
    }

    /**
     * Get the full tree of subsidiaries.
     */
    public function getDescendants(Company $company): Collection
    {
        $descendants = collect();

        foreach ($company->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($this->getDescendants($child));
        }

        return $descendants;
    }

    /**
     * Validate if setting a parent would cause a circular reference.
     */
    public function wouldCauseCircularReference(Company $company, string $parentId): bool
    {
        if ($company->id === $parentId) {
            return true;
        }

        $parent = Company::find($parentId);
        if (!$parent) {
            return false;
        }

        $ancestors = $this->getAncestors($parent);

        return $ancestors->contains('id', $company->id);
    }

    /**
     * Get the hierarchy tree for a workspace.
     */
    public function getTree(string $workspaceId): Collection
    {
        return Company::where('workspace_id', $workspaceId)
            ->whereNull('parent_company_id')
            ->with('children')
            ->get();
    }
}
