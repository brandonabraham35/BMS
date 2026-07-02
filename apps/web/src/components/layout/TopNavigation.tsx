import React from 'react';
import { useAuth } from '../../context/AuthContext';
import { useTheme } from '../../context/ThemeContext';
import { Sun, Moon, LogOut, User } from 'lucide-react';

export const TopNavigation: React.FC = () => {
  const { user, logout } = useAuth();
  const { theme, setTheme } = useTheme();

  return (
    <header className="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 h-16 flex items-center justify-between px-8">
      <div className="text-sm text-slate-500 dark:text-slate-400">
        Enterprise Platform Foundation
      </div>
      <div className="flex items-center space-x-4">
        <button
          onClick={() => setTheme(theme === 'dark' ? 'light' : 'dark')}
          className="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300"
        >
          {theme === 'dark' ? <Sun size={20} /> : <Moon size={20} />}
        </button>
        <div className="flex items-center space-x-2 border-l border-slate-200 dark:border-slate-700 pl-4">
          <div className="flex flex-col items-end">
            <span className="text-sm font-medium text-slate-900 dark:text-white">{user?.name || 'Guest'}</span>
            <span className="text-xs text-slate-500 dark:text-slate-400">{user?.email || ''}</span>
          </div>
          <button
            onClick={logout}
            className="p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600"
          >
            <LogOut size={20} />
          </button>
        </div>
      </div>
    </header>
  );
};
