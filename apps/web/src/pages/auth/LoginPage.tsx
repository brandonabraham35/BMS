import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';
import { useNavigate } from 'react-router-dom';

export const LoginPage: React.FC = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');

    try {
      // In a real app, this would be an API call
      // const response = await api.post('/auth/login', { email, password, device_name: 'browser' });
      // login(response.token, response.user);

      // Mock login for UI foundation
      login('mock-token', { id: '1', name: 'Admin User', email, company_id: '1' });
      navigate('/');
    } catch (err: any) {
      setError(err.message || 'Login failed');
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-slate-50 dark:bg-slate-950 px-4">
      <div className="max-w-md w-full space-y-8 bg-white dark:bg-slate-900 p-8 rounded-xl shadow-lg border border-slate-200 dark:border-slate-800">
        <div>
          <h2 className="text-center text-3xl font-extrabold text-slate-900 dark:text-white">
            BMS Enterprise
          </h2>
          <p className="mt-2 text-center text-sm text-slate-600 dark:text-slate-400">
            Sign in to your account
          </p>
        </div>
        <form className="mt-8 space-y-6" onSubmit={handleSubmit}>
          {error && (
            <div className="bg-red-50 dark:bg-red-900/20 text-red-600 p-3 rounded-lg text-sm border border-red-200 dark:border-red-800">
              {error}
            </div>
          )}
          <div className="rounded-md shadow-sm -space-y-px">
            <div>
              <label className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email address</label>
              <input
                type="email"
                required
                className="appearance-none rounded-t-lg relative block w-full px-3 py-2 border border-slate-300 dark:border-slate-700 placeholder-slate-500 text-slate-900 dark:text-white dark:bg-slate-800 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                placeholder="Email address"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
              />
            </div>
            <div className="mt-4">
              <label className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password</label>
              <input
                type="password"
                required
                className="appearance-none rounded-b-lg relative block w-full px-3 py-2 border border-slate-300 dark:border-slate-700 placeholder-slate-500 text-slate-900 dark:text-white dark:bg-slate-800 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                placeholder="Password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
              />
            </div>
          </div>

          <div className="flex items-center justify-between">
            <div className="flex items-center">
              <input
                id="remember-me"
                name="remember-me"
                type="checkbox"
                className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded"
              />
              <label htmlFor="remember-me" className="ml-2 block text-sm text-slate-900 dark:text-slate-300">
                Remember me
              </label>
            </div>

            <div className="text-sm">
              <a href="/forgot-password" title="Forgot your password?" className="font-medium text-blue-600 hover:text-blue-500">
                Forgot your password?
              </a>
            </div>
          </div>

          <div>
            <button
              type="submit"
              className="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
              Sign in
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};
