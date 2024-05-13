<template>
  <div class="App">
    <Shift @menuOpen="openMenu"  @prev="prevArticle" @next="nextArticle"/>
    <Links v-if="articles.length"
        :links="links"
        @activateTab="activateTab"
        :isOpen="isOpen"/>
    <Article v-if="articles.length"
        :article="activeArticle"/>
  </div>
</template>
<script lang="ts" setup>
import Links from '../entities/Links.vue';
import type {Link, ArticleType} from "./types.ts";
import {onBeforeMount, onMounted, ref, reactive} from "vue";
import Shift from "../entities/Shift.vue";
import Article from "../entities/Article.vue";
import axios from "axios";


const links = reactive<Link[]>([])
const articles = reactive<ArticleType[]>([])

const isOpen = ref(false);
const activeArticle = ref<ArticleType | null>(null);


const openMenu = () => {
  isOpen.value = !isOpen.value
}
const activateTab = (link: Link): void => {
  const foundArticle = articles.find((article) => article.id === link.id);
  if (foundArticle) {
    activeArticle.value = foundArticle;
    links.forEach((item) => {
      item.active = item.id === link.id;
    });
    if (window.matchMedia('(max-width:1336px)').matches) {
      openMenu();
    }
  }
};

const prevArticle = () => {
  const currentIndex = articles.findIndex(article => article.id === activeArticle.value.id);
  if (currentIndex > 0) {
    activeArticle.value = articles[currentIndex - 1];
  }
}
const nextArticle = () => {
  const currentIndex = articles.findIndex(article => article.id === activeArticle.value.id);
  if (currentIndex < articles.length - 1) {
    activeArticle.value = articles[currentIndex + 1];
  }
}

const getArticlesList = () => {
    return axios.post('/ajax/get-articles-list', {})
        .then((res) => {

    })
}
// onMounted(() => {
//     getArticlesList()
// })
onBeforeMount(() => {
    activeArticle.value = articles[0];
});


</script>

<style lang="scss">
</style>
