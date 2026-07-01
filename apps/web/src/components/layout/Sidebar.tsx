import React from 'react';
import { LayoutDashboard, Users, Building2, Settings, ShieldCheck, Bell, Activity } from 'lucide-react';
import { NavLink } from 'react-router-dom';

const navigation = [
  { name: 'Dashboard', href: '/', icon: LayoutDashboard },
  { name: 'Companies', href: '/companies', icon: Building2 },
  { name: 'Branches', href: '/branches', icon: Building2 },
  { name: 'Users', href: '/users', icon: Users },
  { name: 'Roles & Permissions', href: '/rbac', icon: ShieldCheck },
  { name: 'Settings', href: '/settings', icon: Settings },
  { name: 'Notifications', href: '/notifications', icon: Bell },
  { name: 'Audit Logs', href: '/audit-logs', icon: Activity },
];

export const Sidebar: React.FC = () => {
  return (
    <div className="flex flex-col w-64 bg-slate-900 text-white h-screen">
      <div className="flex items-center justify-center h-16 bg-slate-950 font-bold text-xl">
        BMS Enterprise
      </div>
      <nav className="flex-1 px-2 py-4 space-y-1">
        {navigation.map((item) => (
          <NavLink
            key={item.name}
            to={item.href}
            className={({ isActive }) =>
              `flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors ${
                isActive ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'
              }`
            }
          >
            <item.icon className="mr-3 h-5 w-5" />
            {item.name}
          </NavLink>
        ))}
      </nav>
    </div>
  );
};
