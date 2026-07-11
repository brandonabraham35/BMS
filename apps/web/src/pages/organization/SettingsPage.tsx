import React from 'react';
import { useSettings, useUpdateWorkspaceSettings } from '../../hooks/organization/useConfig';
import { Settings as SettingsIcon, Save, RefreshCcw, Info } from 'lucide-react';

export const SettingsPage: React.FC = () => {
  const { data: settings, isLoading, error } = useSettings();
  const updateSettings = useUpdateWorkspaceSettings();

  if (isLoading) return <div className="p-8 text-center text-slate-500">Loading settings...</div>;
  if (error) return <div className="p-8 text-center text-red-500">Error: {(error as Error).message}</div>;

  const [localSettings, setLocalSettings] = React.useState<any[]>([]);

  React.useEffect(() => {
    if (settings?.data) {
      setLocalSettings(settings.data);
    }
  }, [settings]);

  const handleValueChange = (key: string, value: string) => {
    setLocalSettings(prev => prev.map(s => s.key === key ? { ...s, value } : s));
  };

  const handleSave = () => {
    updateSettings.mutate(localSettings.map(s => ({
        key: s.key,
        value: s.value,
        type: s.type || 'string'
    })));
  };

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-3xl font-bold text-slate-900 dark:text-white">Organization Settings</h1>
          <p className="text-slate-500 dark:text-slate-400">Manage hierarchical configurations and platform defaults.</p>
        </div>
        <button
          onClick={handleSave}
          className="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
        >
          <Save className="w-4 h-4 mr-2" />
          Save Changes
        </button>
      </div>

      <div className="grid grid-cols-1 gap-6">
        <div className="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
          <div className="flex items-center mb-6">
            <SettingsIcon className="w-5 h-5 text-blue-600 mr-2" />
            <h2 className="text-lg font-semibold text-slate-900 dark:text-white">General Configuration</h2>
          </div>

          <div className="space-y-6">
            {settings?.data?.length === 0 ? (
                <div className="text-center py-8 text-slate-500 border-2 border-dashed rounded-lg">
                    No configurable settings found in current context.
                </div>
            ) : (
                settings?.data?.map((setting: any) => (
                    <div key={setting.key} className="flex flex-col space-y-2 pb-6 border-b border-slate-100 dark:border-slate-800 last:border-0 last:pb-0">
                        <div className="flex justify-between">
                            <label className="text-sm font-medium text-slate-700 dark:text-slate-300">{setting.key}</label>
                            {setting.is_overridden ? (
                                <span className="flex items-center text-xs text-orange-600 bg-orange-50 dark:bg-orange-900/20 px-2 py-0.5 rounded">
                                    <RefreshCcw className="w-3 h-3 mr-1" />
                                    Overridden
                                </span>
                            ) : (
                                <span className="flex items-center text-xs text-slate-500 bg-slate-50 dark:bg-slate-800 px-2 py-0.5 rounded">
                                    <Info className="w-3 h-3 mr-1" />
                                    Inherited from {setting.source || 'Platform'}
                                </span>
                            )}
                        </div>
                        <input
                            type="text"
                            value={localSettings.find(s => s.key === setting.key)?.value || ''}
                            onChange={(e) => handleValueChange(setting.key, e.target.value)}
                            className="w-full px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-900 dark:text-white"
                        />
                        <p className="text-xs text-slate-400">{setting.description || 'Global application parameter.'}</p>
                    </div>
                ))
            )}
          </div>
        </div>
      </div>
    </div>
  );
};
