import React, { useState } from 'react';

export const ForgotPasswordPage: React.FC = () => {
  const [email, setEmail] = useState('');
  const [submitted, setSubmitted] = useState(false);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitted(true);
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-slate-50 dark:bg-slate-950 px-4">
      <div className="max-w-md w-full space-y-8 bg-white dark:bg-slate-900 p-8 rounded-xl shadow-lg border border-slate-200 dark:border-slate-800 text-center">
        {!submitted ? (
          <>
            <h2 className="text-3xl font-extrabold text-slate-900 dark:text-white">Forgot password?</h2>
            <p className="mt-2 text-sm text-slate-600 dark:text-slate-400">
              No problem. Enter your email and we'll send you a reset link.
            </p>
            <form className="mt-8 space-y-6" onSubmit={handleSubmit}>
              <div>
                <input
                  type="email"
                  required
                  className="appearance-none rounded-lg relative block w-full px-3 py-2 border border-slate-300 dark:border-slate-700 placeholder-slate-500 text-slate-900 dark:text-white dark:bg-slate-800 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  placeholder="Email address"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                />
              </div>
              <button
                type="submit"
                className="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition-colors"
              >
                Send reset link
              </button>
            </form>
          </>
        ) : (
          <div className="py-4">
            <div className="text-green-600 text-5xl mb-4 flex justify-center">✓</div>
            <h2 className="text-2xl font-bold text-slate-900 dark:text-white">Check your email</h2>
            <p className="mt-2 text-slate-600 dark:text-slate-400">
              We've sent a password reset link to {email}.
            </p>
          </div>
        )}
        <div className="mt-4">
          <a href="/login" title="Back to login" className="text-sm font-medium text-blue-600 hover:text-blue-500">Back to login</a>
        </div>
      </div>
    </div>
  );
};
