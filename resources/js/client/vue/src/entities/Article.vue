<template>
  <div class="article">
    <swiper
        :slides-per-view="1"
    >
      <swiper-slide v-for="source in article.src" :key="source">
        <img alt="chel" :src="source" class="article__img"/>
      </swiper-slide>
    </swiper>
    <h1 class="article__title">{{article.title}}</h1>
    <div class="article__text" v-html="article.description">
    </div>
  </div>
</template>
<script lang="ts" setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import type {ArticleType} from "../app/types";
import {ref, watchEffect} from "vue";
interface Props {
  article: ArticleType | null;
}

const props = defineProps<Props>();
const articleRef = ref(props.article);

watchEffect(() => {
  articleRef.value = props.article;
});


</script>
<style lang="scss">
</style>
