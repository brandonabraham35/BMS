import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';

const API_BASE = '/api/v1';

export const useUserTransfers = (userId: string) => {
  return useQuery({
    queryKey: ['user-transfers', userId],
    queryFn: async () => {
      const response = await fetch(`${API_BASE}/users/${userId}/transfers`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`,
          'Accept': 'application/json',
        },
      });
      if (!response.ok) throw new Error('Failed to fetch transfer history');
      return response.json();
    },
    enabled: !!userId,
  });
};

export const useTransferUser = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ userId, data }: { userId: string; data: any }) => {
      const response = await fetch(`${API_BASE}/users/${userId}/transfers`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(data),
      });
      if (!response.ok) throw new Error('Failed to transfer user');
      return response.json();
    },
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ['user-transfers', variables.userId] });
    },
  });
};
