<template>
  <div class="shift-wrapper">
    <div class="shift">
      <span>shift</span>
      <a href="https://t.me/iamshift" class="link-box">
        <span>telegram channel</span>
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M4.2826 17.5962C3.69682 18.182 3.69682 19.1317 4.2826 19.7175C4.86839 20.3033 5.81814 20.3033 6.40392 19.7175L4.2826 17.5962ZM20.157 5.34315C20.157 4.51473 19.4854 3.84315 18.657 3.84315L5.15697 3.84315C4.32854 3.84315 3.65697 4.51472 3.65697 5.34315C3.65697 6.17158 4.32854 6.84315 5.15697 6.84315H17.157V18.8432C17.157 19.6716 17.8285 20.3432 18.657 20.3432C19.4854 20.3432 20.157 19.6716 20.157 18.8432L20.157 5.34315ZM6.40392 19.7175L19.7176 6.40381L17.5963 4.28249L4.2826 17.5962L6.40392 19.7175Z" fill="black"/>
        </svg>
      </a>
    </div>
    <div class="shift-row">
      <div class="shift-all" @click="openMenu">
        <span>все статьи</span>
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M4.2826 17.5962C3.69682 18.182 3.69682 19.1317 4.2826 19.7175C4.86839 20.3033 5.81814 20.3033 6.40392 19.7175L4.2826 17.5962ZM20.157 5.34315C20.157 4.51473 19.4854 3.84315 18.657 3.84315L5.15697 3.84315C4.32854 3.84315 3.65697 4.51472 3.65697 5.34315C3.65697 6.17158 4.32854 6.84315 5.15697 6.84315H17.157V18.8432C17.157 19.6716 17.8285 20.3432 18.657 20.3432C19.4854 20.3432 20.157 19.6716 20.157 18.8432L20.157 5.34315ZM6.40392 19.7175L19.7176 6.40381L17.5963 4.28249L4.2826 17.5962L6.40392 19.7175Z" fill="black"/>
        </svg>
      </div>
      <div class="prev" @click="prev">
        <svg xmlns="http://www.w3.org/2000/svg" width="8" height="14" viewBox="0 0 8 14" fill="none">
          <path d="M8 12.5858L8 1.41421C8 0.523309 6.92286 0.0771398 6.2929 0.707105L0.353553 6.64645C0.158291 6.84171 0.15829 7.15829 0.353552 7.35355L6.29289 13.2929C6.92286 13.9229 8 13.4767 8 12.5858Z" fill="#242424"/>
        </svg>
      </div>
      <div class="next" @click="next">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M8 6.41421L8 17.5858C8 18.4767 9.07714 18.9229 9.7071 18.2929L15.6464 12.3536C15.8417 12.1583 15.8417 11.8417 15.6464 11.6464L9.70711 5.70711C9.07714 5.07714 8 5.52331 8 6.41421Z" fill="#242424"/>
        </svg>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">

  const emit = defineEmits(['menuOpen', 'prev', 'next'])
  const openMenu = () => {
    emit('menuOpen')
  }
  const next = () => {
    emit('next')
  }
  const prev = () => {
    emit('prev')
  }

</script>
<style lang="scss">
</style>