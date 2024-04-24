<template>
  <div class="App">
    <Links :links="links"/>
  </div>
</template>
<script lang="ts" setup>
import Links from '@/entities/Links.vue';
import Article from '@/entities/Article.vue'
import {defineComponent} from "vue";
import {articles} from "@/data/data";
import {links} from "@/data/data";
import Shift  from '@/entities/Shift';

</script>

<style lang="scss">
</style>