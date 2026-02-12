<template>
  <div class="auth-page">
    <div class="auth-container">
      <div class="auth-card">
        <h2>會員註冊</h2>
        <p class="subtitle">加入 G.Collection 會員</p>

        <form @submit.prevent="handleRegister">
          <div class="form-group">
            <label>姓名</label>
            <input
              v-model="name"
              type="text"
              placeholder="請輸入姓名"
              required
            />
          </div>

          <div class="form-group">
            <label>電子郵件</label>
            <input
              v-model="email"
              type="email"
              placeholder="請輸入電子郵件"
              required
            />
          </div>

          <div class="form-group">
            <label>密碼</label>
            <input
              v-model="password"
              type="password"
              placeholder="請輸入密碼（至少 6 位）"
              required
              minlength="6"
            />
          </div>

          <div class="form-group">
            <label>確認密碼</label>
            <input
              v-model="confirmPassword"
              type="password"
              placeholder="請再次輸入密碼"
              required
            />
          </div>

          <button type="submit" class="btn-submit" :disabled="isLoading">
            {{ isLoading ? '註冊中...' : '註冊' }}
          </button>
        </form>

        <div class="auth-footer">
          <p>已經是會員？<NuxtLink to="/login">立即登入</NuxtLink></p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const auth = useAuth();
const router = useRouter();

const name = ref('');
const email = ref('');
const password = ref('');
const confirmPassword = ref('');
const isLoading = ref(false);

const handleRegister = async () => {
  if (password.value !== confirmPassword.value) {
    alert('密碼不一致');
    return;
  }

  isLoading.value = true;
  const result = await auth.register(name.value, email.value, password.value);
  isLoading.value = false;

  if (result.success) {
    router.push('/member');
  } else {
    alert(result.error || '註冊失敗');
  }
};
</script>

<style scoped>
.auth-page {
  min-height: 80vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 20px;
}

.auth-container {
  width: 100%;
  max-width: 450px;
}

.auth-card {
  background: white;
  padding: 50px;
  border-radius: 16px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.auth-card h2 {
  font-size: 2rem;
  color: var(--primary-color);
  text-align: center;
  margin-bottom: 10px;
}

.subtitle {
  text-align: center;
  color: #666;
  margin-bottom: 35px;
}

.form-group {
  margin-bottom: 25px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #333;
}

.form-group input {
  width: 100%;
  padding: 14px 18px;
  border: 2px solid #e0e0e0;
  border-radius: 10px;
  font-size: 1rem;
  transition: all 0.3s;
}

.form-group input:focus {
  outline: none;
  border-color: var(--primary-color);
}

.btn-submit {
  width: 100%;
  padding: 16px;
  background: var(--primary-color);
  color: white;
  border: none;
  border-radius: 10px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
  margin-top: 10px;
}

.btn-submit:hover:not(:disabled) {
  background: var(--secondary-color);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(85, 107, 47, 0.3);
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.auth-footer {
  margin-top: 30px;
  text-align: center;
  color: #666;
}

.auth-footer a {
  color: var(--primary-color);
  text-decoration: none;
  font-weight: 600;
}

.auth-footer a:hover {
  text-decoration: underline;
}
</style>
