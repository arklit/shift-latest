  <template>
    <div class="links-wrapper" :class="{ 'links-wrapper_open': isOpen === true}">
      <div class="links">
        <div
            v-for="link in links"
            :key="link.id"
            :class="{link, active: link.active }"
            @click="setActive(link)"
        >{{ link.text }}
        </div>
      </div>
    </div>
  </template>
  <script lang="ts" setup>
    import {defineProps} from 'vue'

    interface Props {

    }

    const props = defineProps<Props>()
    const {links} = props

    const emit = defineEmits(['activateTab'])

    const setActive = (link: Link) => {
      emit('activateTab', link)
    }
  </script>
  <style lang="scss">
  </style>