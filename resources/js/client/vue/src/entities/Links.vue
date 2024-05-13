  <template>
    <div class="links-wrapper" :class="{ 'links-wrapper_open': openRef === true}">
      <div class="links">
        <div
            v-for="link in activeLinks"
            :key="link.id"
            :class="['link',{ active: link.active} ]"
            @click="setActive(link)"
        >{{ link.text }}
        </div>
      </div>
    </div>
  </template>
  <script lang="ts" setup>
  import {defineProps, reactive, ref, watchEffect} from 'vue'
    import type {Link} from "../app/types.ts";

    interface Props {
      links: Link[] | null,
      isOpen: Boolean
    }

    const props = defineProps<Props>()

    const emit = defineEmits(['activateTab', 'prevArticle', 'nextArticle'])

    const setActive = (link: Link) => {
      emit('activateTab', link)
    }
  const openRef = ref(props.isOpen);
  let activeLinks = reactive(props.links)

  watchEffect(() => {
    openRef.value = props.isOpen;
    activeLinks = props.links
  });

  </script>
  <style lang="scss">
  </style>
