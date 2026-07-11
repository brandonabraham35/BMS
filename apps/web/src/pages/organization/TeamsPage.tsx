import React, { useState } from 'react';
import { useTeams } from '../../hooks/organization/useOrganization';
import { Plus, Search, Users, MoreVertical, Trash2 } from 'lucide-react';

export const TeamsPage: React.FC = () => {
  const [search, setSearch] = useState('');
  const { data: teams, isLoading, error } = useTeams({ search });

  if (isLoading) return <div className="p-8 text-center text-slate-500">Loading teams...</div>;
  if (error) return <div className="p-8 text-center text-red-500">Error: {(error as Error).message}</div>;

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <h1 className="text-3xl font-bold text-slate-900 dark:text-white">Teams</h1>
        <button className="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
          <Plus className="w-4 h-4 mr-2" />
          Add Team
        </button>
      </div>

      <div className="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div className="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center">
          <div className="relative flex-1 max-w-md">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
            <input
              type="text"
              placeholder="Search teams..."
              className="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-900 dark:text-white"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
            />
          </div>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 text-sm font-medium uppercase tracking-wider">
                <th className="px-6 py-3">Team</th>
                <th className="px-6 py-3">Department</th>
                <th className="px-6 py-3">Members</th>
                <th className="px-6 py-3">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-200 dark:divide-slate-800">
              {teams?.data?.length === 0 ? (
                <tr>
                  <td colSpan={4} className="px-6 py-12 text-center text-slate-500">
                    No teams found.
                  </td>
                </tr>
              ) : (
                teams?.data?.map((team: any) => (
                  <tr key={team.id} className="hover:bg-slate-50 dark:hover:bg-slate-950 transition-colors">
                    <td className="px-6 py-4">
                      <div className="flex items-center">
                        <div className="w-10 h-10 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center mr-3">
                          <Users className="w-5 h-5 text-orange-600 dark:text-orange-400" />
                        </div>
                        <div>
                          <div className="font-medium text-slate-900 dark:text-white">{team.name}</div>
                          <div className="text-sm text-slate-500">{team.description || 'No description'}</div>
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                      {team.department_name || 'Unassigned'}
                    </td>
                    <td className="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                      {team.members_count || 0} Members
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex items-center space-x-3 text-slate-400">
                        <button className="hover:text-blue-600 transition-colors">
                          <MoreVertical className="w-4 h-4" />
                        </button>
                        <button className="hover:text-red-600 transition-colors">
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};
