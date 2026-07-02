import React from 'react';
import { Shield, Smartphone, Key, History } from 'lucide-react';

export const SecurityCenterPage: React.FC = () => {
  return (
    <div className="space-y-6">
      <h1 className="text-3xl font-bold text-slate-900 dark:text-white">Security Center</h1>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div className="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
          <div className="flex items-center space-x-3 mb-4">
            <div className="p-2 bg-blue-100 dark:bg-blue-900/20 text-blue-600 rounded-lg">
              <Key size={20} />
            </div>
            <h3 className="font-semibold text-slate-900 dark:text-white">Password</h3>
          </div>
          <p className="text-sm text-slate-500 dark:text-slate-400 mb-4">
            Manage your password and security questions. Last changed 3 months ago.
          </p>
          <button className="text-sm font-medium text-blue-600 hover:text-blue-500">Change password &rarr;</button>
        </div>

        <div className="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
          <div className="flex items-center space-x-3 mb-4">
            <div className="p-2 bg-green-100 dark:bg-green-900/20 text-green-600 rounded-lg">
              <Shield size={20} />
            </div>
            <h3 className="font-semibold text-slate-900 dark:text-white">Two-Factor Authentication</h3>
          </div>
          <p className="text-sm text-slate-500 dark:text-slate-400 mb-4">
            Add an extra layer of security to your account by enabling 2FA.
          </p>
          <button className="text-sm font-medium text-blue-600 hover:text-blue-500">Configure 2FA &rarr;</button>
        </div>

        <div className="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
          <div className="flex items-center space-x-3 mb-4">
            <div className="p-2 bg-purple-100 dark:bg-purple-900/20 text-purple-600 rounded-lg">
              <Smartphone size={20} />
            </div>
            <h3 className="font-semibold text-slate-900 dark:text-white">Trusted Devices</h3>
          </div>
          <p className="text-sm text-slate-500 dark:text-slate-400 mb-4">
            Review and manage devices that are authorized to access your account.
          </p>
          <a href="/devices" title="View devices" className="text-sm font-medium text-blue-600 hover:text-blue-500">View devices &rarr;</a>
        </div>

        <div className="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
          <div className="flex items-center space-x-3 mb-4">
            <div className="p-2 bg-slate-100 dark:bg-slate-800 text-slate-600 rounded-lg">
              <History size={20} />
            </div>
            <h3 className="font-semibold text-slate-900 dark:text-white">Security Log</h3>
          </div>
          <p className="text-sm text-slate-500 dark:text-slate-400 mb-4">
            Monitor recent security-related activities and login attempts.
          </p>
          <a href="/audit-logs" title="View activity" className="text-sm font-medium text-blue-600 hover:text-blue-500">View activity &rarr;</a>
        </div>
      </div>
    </div>
  );
};
