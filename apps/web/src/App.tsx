import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import { ThemeProvider } from './context/ThemeContext';
import { AppLayout } from './components/layout/AppLayout';
import { ProtectedRoute } from './components/ProtectedRoute';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

const queryClient = new QueryClient();

const Dashboard = () => (
  <div className="space-y-6">
    <h1 className="text-3xl font-bold text-slate-900 dark:text-white">Dashboard</h1>
    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
      {[1, 2, 3].map((i) => (
        <div key={i} className="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
          <div className="text-slate-500 dark:text-slate-400 text-sm font-medium mb-1">Module {i}</div>
          <div className="text-2xl font-bold text-slate-900 dark:text-white">Operational</div>
        </div>
      ))}
    </div>
  </div>
);

const Placeholder = ({ title }: { title: string }) => (
  <div className="space-y-6">
    <h1 className="text-3xl font-bold text-slate-900 dark:text-white">{title}</h1>
    <div className="bg-white dark:bg-slate-900 p-8 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 text-center text-slate-500">
      Module content placeholder for {title}
    </div>
  </div>
);

function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <ThemeProvider>
        <AuthProvider>
          <BrowserRouter>
            <Routes>
              <Route element={<ProtectedRoute />}>
                <Route element={<AppLayout />}>
                  <Route path="/" element={<Dashboard />} />
                  <Route path="/companies" element={<Placeholder title="Companies" />} />
                  <Route path="/branches" element={<Placeholder title="Branches" />} />
                  <Route path="/users" element={<Placeholder title="Users" />} />
                  <Route path="/rbac" element={<Placeholder title="Roles & Permissions" />} />
                  <Route path="/settings" element={<Placeholder title="Settings" />} />
                  <Route path="/notifications" element={<Placeholder title="Notifications" />} />
                  <Route path="/audit-logs" element={<Placeholder title="Audit Logs" />} />
                </Route>
              </Route>
            </Routes>
          </BrowserRouter>
        </AuthProvider>
      </ThemeProvider>
    </QueryClientProvider>
  );
}

export default App;
