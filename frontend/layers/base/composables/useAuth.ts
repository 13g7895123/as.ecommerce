import type { User } from '../types';

interface AuthState {
  user: User | null;
  isAuthenticated: boolean;
  loading: boolean;
}

export const useAuth = () => {
  const state = useState<AuthState>('auth', () => ({
    user: null,
    isAuthenticated: false,
    loading: false,
  }));

  // 從 localStorage 載入用戶資料
  if (process.client) {
    const savedUser = localStorage.getItem('user');
    if (savedUser) {
      try {
        state.value.user = JSON.parse(savedUser);
        state.value.isAuthenticated = true;
      } catch (e) {
        console.error('Failed to parse user from localStorage');
      }
    }
  }

  // 登入
  const login = async (email: string, password: string) => {
    state.value.loading = true;
    
    try {
      // 這裡應該調用實際的 API
      // const response = await $fetch('/api/auth/login', {
      //   method: 'POST',
      //   body: { email, password }
      // });
      
      // 模擬 API 回應
      await new Promise(resolve => setTimeout(resolve, 1000));
      
      const user: User = {
        id: '1',
        name: '會員',
        email: email,
      };
      
      state.value.user = user;
      state.value.isAuthenticated = true;
      
      if (process.client) {
        localStorage.setItem('user', JSON.stringify(user));
      }
      
      return { success: true };
    } catch (error) {
      return { success: false, error: '登入失敗' };
    } finally {
      state.value.loading = false;
    }
  };

  // 註冊
  const register = async (name: string, email: string, password: string) => {
    state.value.loading = true;
    
    try {
      // 這裡應該調用實際的 API
      // const response = await $fetch('/api/auth/register', {
      //   method: 'POST',
      //   body: { name, email, password }
      // });
      
      // 模擬 API 回應
      await new Promise(resolve => setTimeout(resolve, 1000));
      
      const user: User = {
        id: '1',
        name: name,
        email: email,
      };
      
      state.value.user = user;
      state.value.isAuthenticated = true;
      
      if (process.client) {
        localStorage.setItem('user', JSON.stringify(user));
      }
      
      return { success: true };
    } catch (error) {
      return { success: false, error: '註冊失敗' };
    } finally {
      state.value.loading = false;
    }
  };

  // 登出
  const logout = () => {
    state.value.user = null;
    state.value.isAuthenticated = false;
    
    if (process.client) {
      localStorage.removeItem('user');
    }
  };

  // 更新用戶資料
  const updateProfile = async (userData: Partial<User>) => {
    if (!state.value.user) return { success: false, error: '未登入' };
    
    state.value.loading = true;
    
    try {
      // 這裡應該調用實際的 API
      // const response = await $fetch('/api/auth/profile', {
      //   method: 'PUT',
      //   body: userData
      // });
      
      await new Promise(resolve => setTimeout(resolve, 1000));
      
      state.value.user = {
        ...state.value.user,
        ...userData,
      };
      
      if (process.client) {
        localStorage.setItem('user', JSON.stringify(state.value.user));
      }
      
      return { success: true };
    } catch (error) {
      return { success: false, error: '更新失敗' };
    } finally {
      state.value.loading = false;
    }
  };

  return {
    user: computed(() => state.value.user),
    isAuthenticated: computed(() => state.value.isAuthenticated),
    loading: computed(() => state.value.loading),
    login,
    register,
    logout,
    updateProfile,
  };
};
