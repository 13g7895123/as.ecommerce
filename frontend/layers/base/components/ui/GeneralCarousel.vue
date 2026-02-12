<template>
  <div class="carousel-wrapper">
    <div 
      ref="carouselRef"
      class="carousel-container"
      @mousedown="handleDragStart"
      @mousemove="handleDragMove"
      @mouseup="handleDragEnd"
      @mouseleave="handleDragEnd"
      @touchstart="handleDragStart"
      @touchmove="handleDragMove"
      @touchend="handleDragEnd"
    >
      <div 
        class="carousel-track"
        :style="{ transform: `translateX(${translateX}px)` }"
      >
        <div
          v-for="(slide, index) in displaySlides"
          :key="`slide-${index}`"
          class="carousel-slide"
        >
          <img :src="slide.image" :alt="slide.title" />
          <div class="slide-content">
            <h2 v-if="slide.title">{{ slide.title }}</h2>
            <p v-if="slide.description">{{ slide.description }}</p>
            <button v-if="slide.buttonText" class="btn-cta">
              {{ slide.buttonText }}
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <button 
      class="carousel-btn prev" 
      @click="prevSlide"
      aria-label="Previous slide"
    >
      <i class="fa-solid fa-chevron-left"></i>
    </button>
    <button 
      class="carousel-btn next" 
      @click="nextSlide"
      aria-label="Next slide"
    >
      <i class="fa-solid fa-chevron-right"></i>
    </button>
    
    <div class="carousel-dots">
      <button
        v-for="(slide, index) in slides"
        :key="`dot-${index}`"
        :class="['dot', { active: index === currentIndex }]"
        @click="goToSlide(index)"
        :aria-label="`Go to slide ${index + 1}`"
      ></button>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Slide {
  image: string;
  title?: string;
  description?: string;
  buttonText?: string;
  link?: string;
}

interface Props {
  slides: Slide[];
  autoPlay?: boolean;
  interval?: number;
  enableDrag?: boolean;
  infiniteLoop?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  autoPlay: true,
  interval: 5000,
  enableDrag: true,
  infiniteLoop: true,
});

const carouselRef = ref<HTMLElement | null>(null);
const currentIndex = ref(0);
const translateX = ref(0);
const isDragging = ref(false);
const startX = ref(0);
const currentTranslate = ref(0);
const previousTranslate = ref(0);
const autoPlayTimer = ref<NodeJS.Timeout | null>(null);

const displaySlides = computed(() => {
  if (!props.infiniteLoop) return props.slides;
  return [
    props.slides[props.slides.length - 1],
    ...props.slides,
    props.slides[0],
  ];
});

const slideWidth = computed(() => {
  return carouselRef.value?.offsetWidth || 0;
});

const updatePosition = () => {
  const offset = props.infiniteLoop ? 1 : 0;
  translateX.value = -(currentIndex.value + offset) * slideWidth.value;
};

const nextSlide = () => {
  currentIndex.value = (currentIndex.value + 1) % props.slides.length;
  updatePosition();
};

const prevSlide = () => {
  currentIndex.value = (currentIndex.value - 1 + props.slides.length) % props.slides.length;
  updatePosition();
};

const goToSlide = (index: number) => {
  currentIndex.value = index;
  updatePosition();
};

const handleDragStart = (e: MouseEvent | TouchEvent) => {
  if (!props.enableDrag) return;
  isDragging.value = true;
  startX.value = 'touches' in e ? e.touches[0].clientX : e.clientX;
  previousTranslate.value = translateX.value;
  if (autoPlayTimer.value) {
    clearInterval(autoPlayTimer.value);
  }
};

const handleDragMove = (e: MouseEvent | TouchEvent) => {
  if (!isDragging.value) return;
  const currentX = 'touches' in e ? e.touches[0].clientX : e.clientX;
  const diff = currentX - startX.value;
  currentTranslate.value = previousTranslate.value + diff;
  translateX.value = currentTranslate.value;
};

const handleDragEnd = () => {
  if (!isDragging.value) return;
  isDragging.value = false;
  
  const movedBy = currentTranslate.value - previousTranslate.value;
  const threshold = slideWidth.value * 0.3;
  
  if (movedBy < -threshold) {
    nextSlide();
  } else if (movedBy > threshold) {
    prevSlide();
  } else {
    updatePosition();
  }
  
  startAutoPlay();
};

const startAutoPlay = () => {
  if (!props.autoPlay) return;
  if (autoPlayTimer.value) {
    clearInterval(autoPlayTimer.value);
  }
  autoPlayTimer.value = setInterval(() => {
    nextSlide();
  }, props.interval);
};

onMounted(() => {
  updatePosition();
  startAutoPlay();
  window.addEventListener('resize', updatePosition);
});

onUnmounted(() => {
  if (autoPlayTimer.value) {
    clearInterval(autoPlayTimer.value);
  }
  window.removeEventListener('resize', updatePosition);
});

watch(() => props.slides.length, () => {
  currentIndex.value = 0;
  updatePosition();
});
</script>

<style scoped>
.carousel-wrapper {
  position: relative;
  width: 100%;
  overflow: hidden;
  border-radius: 12px;
}

.carousel-container {
  position: relative;
  width: 100%;
  cursor: grab;
  user-select: none;
}

.carousel-container:active {
  cursor: grabbing;
}

.carousel-track {
  display: flex;
  transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  will-change: transform;
}

.carousel-slide {
  position: relative;
  min-width: 100%;
  height: 600px;
  flex-shrink: 0;
}

.carousel-slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.slide-content {
  position: absolute;
  bottom: 80px;
  left: 60px;
  color: white;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
  max-width: 600px;
}

.slide-content h2 {
  font-size: 3.5rem;
  font-weight: 800;
  margin-bottom: 20px;
  line-height: 1.2;
}

.slide-content p {
  font-size: 1.3rem;
  margin-bottom: 30px;
  line-height: 1.6;
}

.btn-cta {
  padding: 16px 40px;
  background: white;
  color: #333;
  border: none;
  border-radius: 30px;
  font-size: 1.1rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-cta:hover {
  background: var(--primary-color, #333);
  color: white;
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

.carousel-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(255, 255, 255, 0.9);
  color: #333;
  border: none;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 1.2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s;
  z-index: 10;
}

.carousel-btn:hover {
  background: white;
  transform: translateY(-50%) scale(1.1);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.carousel-btn.prev {
  left: 20px;
}

.carousel-btn.next {
  right: 20px;
}

.carousel-dots {
  position: absolute;
  bottom: 30px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 12px;
  z-index: 10;
}

.dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.5);
  border: 2px solid white;
  cursor: pointer;
  transition: all 0.3s;
}

.dot.active {
  background: white;
  transform: scale(1.3);
}

@media (max-width: 768px) {
  .carousel-slide {
    height: 400px;
  }

  .slide-content {
    left: 30px;
    bottom: 60px;
  }

  .slide-content h2 {
    font-size: 2rem;
  }

  .slide-content p {
    font-size: 1rem;
  }

  .carousel-btn {
    width: 40px;
    height: 40px;
  }
}
</style>
