<template>
  <div class="article">
    <swiper
        :slides-per-view="1"
    >
      <swiper-slide v-for="source in src" :key="source">
        <img alt="chel" :src="source" class="article__img"/>
      </swiper-slide>
    </swiper>
    <h1 class="article__title">{{title}}</h1>
    <div class="article__text" v-html="text">
    </div>
  </div>
</template>
<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';

 export default {
   name: "Article",
   components: {Swiper, SwiperSlide},
   props: {
     title: String,
     text: {
       type: String,
       default: '',
     },
     src: Array,
   }


 }
</script>
<style lang="scss">
</style>