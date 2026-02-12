<template>
  <footer class="app-footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-col">
          <h4>關於我們</h4>
          <ul>
            <li>品牌故事</li>
            <li>門市據點</li>
            <li>設計師團隊</li>
            <li>企業合作</li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>客戶服務</h4>
          <ul>
            <li>配送方式</li>
            <li>退換貨政策</li>
            <li>付款方式</li>
            <li>常見問題</li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>聯絡我們</h4>
          <ul>
            <li>Email: {{ contactEmail }}</li>
            <li>電話: {{ contactPhone }}</li>
            <li>營業時間: {{ businessHours }}</li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>追蹤我們</h4>
          <ul>
            <li><i class="fa-brands fa-facebook"></i> Facebook</li>
            <li><i class="fa-brands fa-instagram"></i> Instagram</li>
            <li><i class="fa-brands fa-pinterest"></i> Pinterest</li>
          </ul>
        </div>
      </div>
      <div class="copyright">
        &copy; {{ currentYear }} {{ brandName }}. All rights reserved. | {{ slogan }}
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
interface Props {
  brandName?: string;
  slogan?: string;
  contactEmail?: string;
  contactPhone?: string;
  businessHours?: string;
}

const props = withDefaults(defineProps<Props>(), {
  brandName: 'COLLECTION',
  slogan: '品味生活，從細節開始',
  contactEmail: 'hello@collection.com',
  contactPhone: '02-1234-5678',
  businessHours: '週一至週日 10:00-21:00',
});

const currentYear = new Date().getFullYear();
</script>

<style scoped>
.app-footer {
  background: linear-gradient(to top, var(--primary-color, #333), var(--secondary-color, #555));
  color: white;
  padding: 60px 0 30px;
  margin-top: 80px;
}

.footer-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 40px;
  margin-bottom: 40px;
}

.footer-col h4 {
  font-size: 1.1rem;
  font-weight: 700;
  margin-bottom: 20px;
  color: rgba(255, 255, 255, 0.95);
}

.footer-col ul {
  list-style: none;
}

.footer-col ul li {
  margin-bottom: 12px;
  color: rgba(255, 255, 255, 0.8);
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.3s;
}

.footer-col ul li:hover {
  color: white;
  padding-left: 5px;
}

.copyright {
  text-align: center;
  padding-top: 30px;
  border-top: 1px solid rgba(255, 255, 255, 0.2);
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.9rem;
}
</style>
