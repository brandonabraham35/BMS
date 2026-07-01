import React from 'react';
import { Sidebar } from './Sidebar';
import { TopNavigation } from './TopNavigation';
import { Outlet } from 'react-router-dom';

export const AppLayout: React.FC = () => {
  return (
    <div className="flex h-screen bg-slate-50 dark:bg-slate-950">
      <Sidebar />
      <div className="flex-1 flex flex-col overflow-hidden">
        <TopNavigation />
        <main className="flex-1 overflow-x-hidden overflow-y-auto p-8">
          <Outlet />
        </main>
      </div>
    </div>
  );
};
