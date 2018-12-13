<template>
  <div>
    <drawer />
    <navbar />
    <div class="app-content is-open">
      <router-view />
    </div>
  </div>
</template>

<script>
import { Navbar, Drawer } from './components'
export default {
  name: 'Layout',
  components: {
    Navbar,
    Drawer
  }
}
</script>

<style scoped>
    .app-content {
        /*padding-top: 64px;*/
        padding-top: 84px;
    }
    .app-content.is-open {
        padding-left: 256px;
    }
</style>
