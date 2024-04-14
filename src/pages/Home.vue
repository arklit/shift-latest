<script setup lang="ts">
import {useCounterStore} from "@/stores/counter";

let store = useCounterStore()
</script>

<template>
  <main>
    <div style="display: flex; align-items: center; gap: 30px">
      <span>{{store.count}}</span>
      <button @click="store.increment()">counter++</button>
    </div>
  </main>
</template>
