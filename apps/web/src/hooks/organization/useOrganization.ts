import { useQuery } from '@tanstack/react-query';

const API_BASE = '/api/v1';

export const useBranches = (params = {}) => {
  return useQuery({
    queryKey: ['branches', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams(params);
      const response = await fetch(`${API_BASE}/branches?${searchParams.toString()}`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`,
          'Accept': 'application/json',
        },
      });
      if (!response.ok) throw new Error('Failed to fetch branches');
      return response.json();
    },
  });
};

export const useDepartments = (params = {}) => {
  return useQuery({
    queryKey: ['departments', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams(params);
      const response = await fetch(`${API_BASE}/departments?${searchParams.toString()}`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`,
          'Accept': 'application/json',
        },
      });
      if (!response.ok) throw new Error('Failed to fetch departments');
      return response.json();
    },
  });
};

export const useTeams = (params = {}) => {
  return useQuery({
    queryKey: ['teams', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams(params);
      const response = await fetch(`${API_BASE}/teams?${searchParams.toString()}`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`,
          'Accept': 'application/json',
        },
      });
      if (!response.ok) throw new Error('Failed to fetch teams');
      return response.json();
    },
  });
};
