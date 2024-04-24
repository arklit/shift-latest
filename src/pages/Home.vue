<script setup lang="ts">
import {useCounterStore} from "@/stores/counter";

let store = useCounterStore()
</script>

<template>
  <router-view></router-view>
</template>
