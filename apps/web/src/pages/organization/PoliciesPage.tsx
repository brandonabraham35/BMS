import React from 'react';
import { usePolicies } from '../../hooks/organization/useConfig';
import { ShieldCheck, Plus, ExternalLink } from 'lucide-react';

export const PoliciesPage: React.FC = () => {
  const { data: policies, isLoading, error } = usePolicies();

  if (isLoading) return <div className="p-8 text-center text-slate-500">Loading policies...</div>;
  if (error) return <div className="p-8 text-center text-red-500">Error: {(error as Error).message}</div>;

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-3xl font-bold text-slate-900 dark:text-white">Organization Policies</h1>
          <p className="text-slate-500 dark:text-slate-400">Define business rules, security constraints, and operational standards.</p>
        </div>
        <button className="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
          <Plus className="w-4 h-4 mr-2" />
          Create Policy
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {policies?.data?.length === 0 ? (
            <div className="col-span-full text-center py-12 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-500">
                No active policies found.
            </div>
        ) : (
            policies?.data?.map((policy: any) => (
                <div key={policy.id} className="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 hover:border-blue-500 dark:hover:border-blue-400 transition-colors group">
                    <div className="flex justify-between items-start mb-4">
                        <div className="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <ShieldCheck className="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <button className="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                            <ExternalLink className="w-4 h-4" />
                        </button>
                    </div>
                    <h3 className="font-semibold text-slate-900 dark:text-white mb-1">{policy.name}</h3>
                    <p className="text-sm text-slate-500 dark:text-slate-400 mb-4">{policy.type.replace('_', ' ')}</p>

                    <div className="flex items-center justify-between pt-4 border-t border-slate-50 dark:border-slate-800">
                        <span className={`px-2 py-0.5 rounded text-xs font-medium ${
                            policy.is_active
                            ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400'
                            : 'bg-slate-50 text-slate-700 dark:bg-slate-800 dark:text-slate-400'
                        }`}>
                            {policy.is_active ? 'Active' : 'Draft'}
                        </span>
                        <span className="text-xs text-slate-400 group-hover:text-blue-500 transition-colors cursor-pointer">
                            View Rules →
                        </span>
                    </div>
                </div>
            ))
        )}
      </div>
    </div>
  );
};
