import React from 'react';
import { useUserTransfers } from '../../hooks/organization/useTransfers';
import { MoveHorizontal, Calendar, User, ArrowRight } from 'lucide-react';

export const UserTransfersPage: React.FC<{ userId: string }> = ({ userId }) => {
  const { data: transfers, isLoading, error } = useUserTransfers(userId);

  if (isLoading) return <div className="p-8 text-center text-slate-500">Loading transfer history...</div>;
  if (error) return <div className="p-8 text-center text-red-500">Error: {(error as Error).message}</div>;

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-2xl font-bold text-slate-900 dark:text-white">Transfer History</h1>
          <p className="text-sm text-slate-500">Audit trail of organizational movements for this user.</p>
        </div>
      </div>

      <div className="space-y-4">
        {transfers?.data?.length === 0 ? (
          <div className="text-center py-12 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-500">
            No transfer records found.
          </div>
        ) : (
          transfers?.data?.map((transfer: any) => (
            <div key={transfer.id} className="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
              <div className="flex items-start justify-between mb-4">
                <div className="flex items-center">
                  <div className="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg mr-3">
                    <MoveHorizontal className="w-5 h-5 text-blue-600 dark:text-blue-400" />
                  </div>
                  <div>
                    <div className="font-semibold text-slate-900 dark:text-white">
                      {transfer.reason || 'Organizational Reassignment'}
                    </div>
                    <div className="flex items-center text-xs text-slate-500 mt-1">
                      <Calendar className="w-3 h-3 mr-1" />
                      {new Date(transfer.transferred_at).toLocaleDateString()}
                      <span className="mx-2">•</span>
                      <User className="w-3 h-3 mr-1" />
                      By System Admin
                    </div>
                  </div>
                </div>
                <span className="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded text-xs font-medium">
                  Completed
                </span>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-50 dark:border-slate-800">
                <div className="space-y-1">
                  <div className="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Source</div>
                  <div className="text-sm text-slate-600 dark:text-slate-300">
                    {transfer.previous_state?.branch_id || 'Initial Assignment'}
                  </div>
                </div>
                <div className="space-y-1">
                  <div className="text-[10px] uppercase font-bold text-slate-400 tracking-wider flex items-center">
                    Destination <ArrowRight className="w-3 h-3 ml-1" />
                  </div>
                  <div className="text-sm text-slate-900 dark:text-white font-medium">
                    {transfer.new_state?.branch_id || 'Unknown'}
                  </div>
                </div>
              </div>
            </div>
          ))
        )}
      </div>
    </div>
  );
};
