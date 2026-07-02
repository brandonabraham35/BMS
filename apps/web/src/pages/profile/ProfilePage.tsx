import React from 'react';
import { useAuth } from '../../context/AuthContext';

export const ProfilePage: React.FC = () => {
  const { user } = useAuth();

  return (
    <div className="space-y-6">
      <h1 className="text-3xl font-bold text-slate-900 dark:text-white">Profile</h1>
      <div className="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div className="p-8 flex items-center space-x-6">
          <div className="h-24 w-24 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-3xl font-bold text-slate-500">
            {user?.name?.charAt(0) || 'U'}
          </div>
          <div>
            <h2 className="text-2xl font-bold text-slate-900 dark:text-white">{user?.name}</h2>
            <p className="text-slate-500 dark:text-slate-400">{user?.email}</p>
            <div className="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
              Active Account
            </div>
          </div>
        </div>
        <div className="border-t border-slate-200 dark:border-slate-800 p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
          <div>
            <h3 className="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Account Details</h3>
            <div className="mt-4 space-y-4">
              <div>
                <span className="block text-sm text-slate-500">Company ID</span>
                <span className="text-slate-900 dark:text-white">{user?.company_id}</span>
              </div>
              <div>
                <span className="block text-sm text-slate-500">Member Since</span>
                <span className="text-slate-900 dark:text-white">January 2024</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
